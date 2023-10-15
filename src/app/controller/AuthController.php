<?php
	namespace bikeshop\app\controller;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\core\authentication\JwtPayload;
	use bikeshop\app\core\authentication\JwtToken;
	use bikeshop\app\core\Controller;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\database\models\DbUserModel;
	use bikeshop\app\models\CreateAccountModel;
	use bikeshop\app\models\LoginModel;
	use bikeshop\app\models\LoginSuccessModel;
	use bikeshop\app\models\ModelBase;
	use DateInterval;
	use DateTime;
	use Exception;
	
	class AuthController extends Controller
	{
		private DatabaseConnector $databaseConnector;
		
		public function __construct()
		{
			$this->databaseConnector = new DatabaseConnector("user", "password", "BIKE_SHOP");
		
			// Check to make sure that there is a SYSADMIN user
			$env = new ArrayWrapper($_ENV);
			
			if ($env->keyExists('__SYSADMIN_EMAIL') && $env->keyExists('__SYSADMIN_PASS'))
			{
				$email = $env->getValueWithKey('__SYSADMIN_EMAIL');
				$password = $env->getValueWithKey('__SYSADMIN_PASS');
				if (!$this->databaseConnector->findUserWithEmailAddress($email))
				{
					$this->databaseConnector->insertUser(new CreateAccountModel($email, $password, null, [], 4));
				}
			}
		}
		
		/**
		 * @throws Exception
		 */
		public function login(ApplicationState $state) : void
		{
			if ($_SERVER[ "REQUEST_METHOD" ] == "GET") {
				$this->view('auth', 'login');
				return;
			}
			
			$credentials = null;
			
			// Get Login Credentials
			try {
				$credentials = $this->getLoginCredentials(new ArrayWrapper($_POST), $state);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			
			if (!( $credentials instanceof LoginModel ))
				throw new Exception("An error occurred while trying to gather user credentials (cannot convert " . $credentials::class . " to " . LoginModel::class . ").");
			
			// If the credentials are correct, ensure that it was retrieved correctly
			$foundUser = $this->validateCredentials($credentials);
			
			if (!( $foundUser instanceof DbUserModel )) {
				throw new Exception("This account doesn't exist, or was deleted. Please sign in again or create an account");
			}
			
			// Create Jwt
			$expiry = new DateTime();
			$expiryTime = new DateInterval("P1M"); // 30M = 30 minutes, P is required for date intervals
			$expiry->add($expiryTime);
			
			$payload = new JwtPayload('localhost', new DateTime(), $expiry, $foundUser->getId());
			
			$token = new JwtToken([], $payload);
			
			$this->view('auth', 'login', new LoginSuccessModel($token, $state));
		}
		
		public function createAccount(ApplicationState $state) : void
		{
			if ($_SERVER[ "REQUEST_METHOD" ] == "GET")
			{
				$userRoles = $this->databaseConnector->selectAllUserRoles();
				$this->view('auth', 'create-account', new CreateAccountModel("", "", $state, $userRoles));
			}
			else
			{
				$account = null;
				// get data
				try {
					$account = $this->getCreateAccountDetails(new ArrayWrapper($_POST), $state);
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				
				if ($account instanceof CreateAccountModel) {
					// User is not null, insert it into the database
					try {
						$this->databaseConnector->insertUser($account);
					} catch (Exception $e) {
						echo "<p>An account with this email address already exists. Either log in or reset your password</p>";
					}
				}
			}
		}
		
		/**
		 * Checks an array to collect all details used to create an account
		 * @param $arr array The array that contains the request method
		 * @throws Exception if a GET request was performed instead of a POST or if username/password fileds are wrong
		 */
		private function getCreateAccountDetails(ArrayWrapper $arr, ApplicationState $state) : CreateAccountModel | null
		{
			// TODO - Instead of throwing exceptions, reroute to login() and show errors.
			// TODO - Rename 'emailAddress' to 'email' like a sensible person
			
			// Check if emailAddress exists, and if it is of correct length
			$emailAddress = $arr->getValueWithKey('email');
			if (empty($emailAddress) || strlen($emailAddress) > 50)
				throw new Exception("Username has invalid size. Must be between 1 - 50 letters long");
			$emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);
			
			// Check if password exists, and if it is of correct length
			$password = $arr->getValueWithKey('password');
			
			// Check User Role Id. Can be null
			if ($arr->keyExists('user-role'))
				$userRole = $arr->getValueWithKey('user-role');
			else
				$userRole = 1;
			
			return new CreateAccountModel($emailAddress, $password, $state, [], $userRole);
		}
		
		/**
		 * Checks an array to see if there is an email or password field inside it
		 * @param $arr array The array that contains the request method
		 * @throws Exception if a GET request was performed instead of a POST or if username/password fileds are wrong
		 * @returns LoginModel coming from the array, filtered and sanatised
		 */
		private function getLoginCredentials(ArrayWrapper $arr, ApplicationState $state) : LoginModel | null
		{
			// TODO - Instead of throwing exceptions, reroute to login() and show errors.
			// TODO - Rename 'emailAddress' to 'email' like a sensible person
			
			// Check if emailAddress exists, and if it is of correct length
			$emailAddress = $arr->getValueWithKey('email');
			if (strlen($emailAddress) > 50)
				throw new Exception("Username has invalid size. Must be between 1 - 50 letters long");
			$emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);
			
			// Check if password exists, and if it is of correct length
			$password = $arr->getValueWithKey('password');
			if (strlen($password) > 50)
				throw new Exception("Password has invalid size. Must be between 1 - 50 letters long");
			
			return new LoginModel($emailAddress, $password, $state);
		}
		
		/**
		 * @throws Exception if the user puts in the wrong password
		 */
		private function validateCredentials(LoginModel $credentials) : DbUserModel | null
		{
			// Check if user exists
			$user = $this->databaseConnector->findUserWithEmailAddress($credentials->getEmail());
			if (empty($user))
				throw new Exception("Invalid email or password");
			
			if (password_verify($credentials->getPassword(), $user->getPassword()))
				return $user; else
				throw new Exception("Invalid email or password");
		}
	}