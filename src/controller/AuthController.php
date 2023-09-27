<?php
	require_once '../models/CreateAccountModel.php';
	require_once '../models/LoginModel.php';
	require_once '../core/jwt/JwtPayload.php';
	require_once '../core/jwt/JwtToken.php';
	
	class AuthController extends Controller
	{
		private DatabaseConnector $databaseConnector;
		
		public function __construct()
		{
			$this->databaseConnector = new DatabaseConnector("user", "password", "BIKE_SHOP");
		}
		
		/**
		 * @throws Exception
		 */
		public function login(): void
		{
			$credentials = null;
			
			if ($_SERVER["REQUEST_METHOD"] == "GET")
			{
				$this->view('auth', 'login');
			}
			else
			{
				try
				{
					$credentials = $this->getLoginCredentials($_POST);
				}
				catch (Exception $e)
				{
					echo $e->getMessage();
				}
				
				if (!($credentials instanceof LoginModel))
					throw new Exception("An error occurred while trying to gather user credentials (cannot convert " . $credentials::class . " to " . LoginModel::class . ").");
				
				
				// Create Jwt
				$expiry = new DateTime();
				$expiryTime = new DateInterval("P30M"); // 30M = 30 minutes, P is required for date intervals
				$expiry->add($expiryTime);
				
				$payload = new JwtPayload(
					'localhost',
					new DateTime(),
					$expiry,
					[]
				);
				
				$token = new JwtToken([], $payload);
				
				$this->view('auth', 'login', new ModelBase($token->encode()));
			}
		}
		
		public function createAccount(): void
		{
			if (!$_SERVER["REQUEST_METHOD"] == "POST")
			{
				return;
			}
			
			$account = null;
			// get data
			try
			{
				$account = $this->getCreateAccountDetails($_POST);
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
			
			if ($account instanceof CreateAccountModel)
			{
				// User is not null, insert it into the database
				try
				{
					$this->databaseConnector->insertUser($account);
				}
				catch (Exception $e)
				{
					echo "<p>An account with this email address already exists. Either log in or reset your password</p>";
				}
			}

//			$this->view('auth', 'create-account');
		}
		
		/**
		 * Checks an array to collect all details used to create an account
		 * @param $arr array The array that contains the request method
		 * @throws Exception if a GET request was performed instead of a POST or if username/password fileds are wrong
		 */
		private function getCreateAccountDetails(array $arr) : CreateAccountModel|null
		{
			// TODO - Instead of throwing exceptions, reroute to login() and show errors.
			// TODO - Rename 'emailAddress' to 'email' like a sensible person
			
			// Check if emailAddress exists, and if it is of correct length
			if (!array_key_exists("email", $arr)) throw new Exception("No emailAddress was provided");
			
			$emailAddress = $arr["email"];
			
			if (empty($emailAddress) || strlen($emailAddress) > 50) throw new Exception("Username has invalid size. Must be between 1 - 50 letters long");
			$emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);
			
			// Check if password exists, and if it is of correct length
			if (!array_key_exists("password", $arr)) throw new Exception("No password was provided");
			
			$password = $arr["password"];
			
			// TODO - Password (and email) should have infinite string length (or at least 256)
			if (empty($password) || strlen($password) > 50) throw new Exception("Password has invalid size. Must be between 1 - 50 letters long");
			
			return new CreateAccountModel($emailAddress, $password);
		}
		
		/**
		 * Checks an array to see if there is an email or password field inside it
		 * @param $arr array The array that contains the request method
		 * @throws Exception if a GET request was performed instead of a POST or if username/password fileds are wrong
		 * @returns LoginModel coming from the array, filtered and sanatised
		 */
		private function getLoginCredentials(array $arr) : LoginModel|null
		{
			// TODO - Instead of throwing exceptions, reroute to login() and show errors.
			// TODO - Rename 'emailAddress' to 'email' like a sensible person
			
			// Check if emailAddress exists, and if it is of correct length
			if (!array_key_exists("email", $arr)) throw new Exception("No emailAddress was provided");
			
			$emailAddress = $arr["email"];
			
			if (empty($emailAddress) || strlen($emailAddress) > 50) throw new Exception("Username has invalid size. Must be between 1 - 50 letters long");
			$emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);
			
			// Check if password exists, and if it is of correct length
			if (!array_key_exists("password", $arr)) throw new Exception("No password was provided");
			
			$password = $arr["password"];
			
			// TODO - Password (and email) should have infinite string length (or at least 256)
			if (empty($password) || strlen($password) > 50) throw new Exception("Password has invalid size. Must be between 1 - 50 letters long");
			
			return new LoginModel(null, $emailAddress, $password);
		}
		
		private function validateCredentials(LoginModel $credentials): bool
		{
			// Check if user exists
			$user = $this->databaseConnector->findUserWithEmailAddress($credentials->getEmail());
			if (empty($user))
			{
				return false;
			}
			
			return password_verify($credentials->getPassword(), $user->getPassword());
		}
	}