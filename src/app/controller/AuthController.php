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
	use bikeshop\app\models\EditUserModel;
	use bikeshop\app\models\LoginModel;
	use bikeshop\app\models\LoginSuccessModel;
	use bikeshop\app\models\ModelBase;
	use DateInterval;
	use DateTime;
	use Exception;
	
	class AuthController extends Controller
	{
		private DatabaseConnector $db;
		
		public function __construct()
		{
			$this->db = new DatabaseConnector("user", "password", "BIKE_SHOP");
		
			// Check to make sure that there is a SYSADMIN user
			$env = new ArrayWrapper($_ENV);
			
			if ($env->keyExists('__SYSADMIN_EMAIL') && $env->keyExists('__SYSADMIN_PASS'))
			{
				$email = $env->getValueWithKey('__SYSADMIN_EMAIL');
				$password = $env->getValueWithKey('__SYSADMIN_PASS');
				if (!$this->db->findUserWithEmailAddress($email))
				{
					$this->db->insertUser(new CreateAccountModel($email, $password, null, [], 4));
				}
			}
		}
		
		/**
		 * @throws Exception
		 */
		public function login(ApplicationState $state) : void
		{
			if ($_SERVER[ "REQUEST_METHOD" ] == "GET") {
				$this->deprecatedView('auth', 'login');
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
			
			$this->deprecatedView('auth', 'login', new LoginSuccessModel($token, $state));
		}
		
		public function logout(ApplicationState $state): void
		{
			if ($state->getUser())
			{
				$state->setUser(null);
				$this->deprecatedView('auth', 'logout');
			}
		}
		
		public function createAccount(ApplicationState $state) : void
		{
			if ($_SERVER[ "REQUEST_METHOD" ] == "GET")
			{
				$userRoles = $this->db->selectAllUserRoles();
				$this->deprecatedView('auth', 'create-account', new CreateAccountModel("", "", $state, $userRoles));
			}
			else
			{
				$account = null;
				// get data
				try
				{
					$account = $this->getCreateAccountDetails(new ArrayWrapper($_POST), $state);
				}
				catch (Exception $e)
				{
					echo $e->getMessage();
				}
				
				if ($account instanceof CreateAccountModel) {
					// User is not null, insert it into the database
					try
					{
						$this->db->insertUser($account);
					}
					catch (Exception $e)
					{
						echo "<p>An account with this email address already exists. Either log in or reset your password</p>";
					}
				}
				
				$this->deprecatedView("auth", "login");
			}
		}
		
		/**
		 * @throws Exception
		 */
		public function editAccount(ApplicationState $state)
		{
			if ($_SERVER['REQUEST_METHOD'] == 'GET')
			{
				// Get Account Id
				$get = new ArrayWrapper($_GET);
				$accountId = $this->getAccountIdToEdit($state);
				if ($accountId == null) throw new Exception("Unauthorised");
				
				
				// Make sure manager exists in database
				$user = $this->db->findUserWithId($accountId);
				
				if ($user == null)
					throw new Exception("No manager found with provided ID " . $accountId);
				
				$model = new EditUserModel($user, $state);
				
				// Return view
				$this->deprecatedView('auth', 'edit-account', $model);
			}
			else
			{
				// Make sure user id and the user itself exists in the database
				$post = new ArrayWrapper($_POST);
				
				$id = $post->getValueWithKey('id');
				if ($id == null) throw new Exception("Cannot edit user without an ID");
				
				$user = $this->db->findUserWithId($id);
				if ($user == null) throw new Exception("No user found with provided Id" . $id);
				
				# region Setting Values
				
				if ($post->keyExists('email'))
					$user->setEmailAddress($post->getValueWithKey('email'));
				if ($post->keyExists('first-name'))
					$user->setFirstName($post->getValueWithKey('first-name'));
				if ($post->keyExists('last-name'))
					$user->setLastName($post->getValueWithKey('last-name'));
				if ($post->keyExists('address'))
					$user->setAddress($post->getValueWithKey('address'));
				if ($post->keyExists('suburb'))
					$user->setSuburb($post->getValueWithKey('suburb'));
				if ($post->keyExists('state'))
					$user->setState($post->getValueWithKey('state'));
				if ($post->keyExists('postcode'))
					$user->setPostcode($post->getValueWithKey('postcode'));
				if ($post->keyExists('country'))
					$user->setCountry($post->getValueWithKey('country'));
				if ($post->keyExists('phone'))
					$user->setPhone($post->getValueWithKey('phone'));
				
				# endregion
				
				// If a new password has been provided, re-hash it and update it
				if ($post->keyExists('password'))
				{
					$newPass = password_hash($post->getValueWithKey('password'), PASSWORD_BCRYPT);
					$user->setPassword($newPass);
				}
				
				$this->db->updateUser($user);
			}
		}

		/**
		 * Gets the account id from the user, whether it be as a parameter from the URL or the logged in account
	     * @return int | null The id found; null if user is anonymous
		 */
		private function getAccountIdToEdit(ApplicationState $state): int | null
		{
			$get = new ArrayWrapper($_GET);
			
			// Currently logged in as | Where to get ID
			//              Anonymous | Null
			//                 Member | From logged in account
			//                  Staff | From logged in account
			//              Sys Admin | From URL param, If null, then account
			
			if (!$state->getUser())
			{
				return null;
			}
			
			$roleId = $state->getUser()->getUserRoleId();
			switch ($roleId) {
				case 2:
				case 1:
					return $state->getUser()->getId();
				case 4:
				case 3:
					return $get->getValueWithKey('a') ?? $state->getUser()->getId();
				default:
					return null;
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
			$user = $this->db->findUserWithEmailAddress($credentials->getEmail());
			if (empty($user))
				throw new Exception("Invalid email or password");
			
			if (password_verify($credentials->getPassword(), $user->getPassword()))
				return $user; else
				throw new Exception("Invalid email or password");
		}
	}