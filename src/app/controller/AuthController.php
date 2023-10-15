<?php
	namespace bikeshop\app\controller;
	use bikeshop\app\core\ActionResult;
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
			if ($_SERVER[ "REQUEST_METHOD" ] == "GET")
			{
				$this->view(new ActionResult('auth', 'login'));
				return;
			}
			else if ($_SERVER[ "REQUEST_METHOD"] == "POST")
			{
				$credentials = null;
				
				// Get Login Credentials
				try
				{
					$credentials = $this->getLoginCredentials(new ArrayWrapper($_POST), $state);
				}
				catch (Exception $e)
				{
					$this->view(parent::http400ResponseAction());
					return;
				}
				
				if (!( $credentials instanceof LoginModel ))
				{
					$this->view(parent::http400ResponseAction());
					return;
				}
				
				// If the credentials are correct, ensure that it was retrieved correctly
				$foundUser = $this->validateCredentials($credentials);
				
				if (!( $foundUser instanceof DbUserModel ))
				{
					$this->view(parent::http401ResponseAction());
					return;
				}
				
				// Create Jwt
				$expiry = new DateTime();
				$expiryTime = new DateInterval("P1M"); // 30M = 30 minutes, P is required for date intervals
				$expiry->add($expiryTime);
				
				$payload = new JwtPayload('localhost', new DateTime(), $expiry, $foundUser->getId());
				
				$token = new JwtToken([], $payload);
				
				$this->view(new ActionResult('auth', 'login', new LoginSuccessModel($token, $state)));
			}
			else
			{
				$this->view($this->http405ResponseAction());
			}
		}
		
		public function logout(ApplicationState $state): void
		{
			if ($_SERVER["REQUEST_METHOD"] != 'GET')
				$this->view($this->http405ResponseAction());
			
			if ($state->getUser())
			{
				$state->setUser(null);
				$this->view(new ActionResult('auth', 'logout'));
			}
		}
		
		public function createAccount(ApplicationState $state) : void
		{
			if ($_SERVER[ "REQUEST_METHOD" ] == "GET")
			{
				$userRoles = $this->db->selectAllUserRoles();
				$this->view(new ActionResult('auth', 'create-account', new CreateAccountModel("", "", $state, $userRoles)));
			}
			else if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$account = null;
				// get data
				try
				{
					$account = $this->getCreateAccountDetails(new ArrayWrapper($_POST), $state);
				}
				catch (Exception $e)
				{
					$this->view(parent::http403ResponseAction());
					return;
				}
				
				if ($account instanceof CreateAccountModel)
				{
					// Sysadmins (id = 4) can create any account
					// Managers (id = 3) can only create other managers and staff
					// Staff (id = 2) can only create member accounts
					// Members (id = 1) are only allowed to create a member account
					$user = $state->getUser();
					if ($user)
					{
						switch ($user->getUserRoleId())
						{
							case 3:
								if ($account->getRoleId() == 4)
								{
									$this->view($this->http401ResponseAction());
									return;
								}
								break;
							case 2:
							case 1:
								if ($account->getRoleId() != 1)
								{
									$this->view($this->http401ResponseAction());
									return;
								}
								break;
							default:
								break;
						}
					}
					
					// User is not null, insert it into the database
					try
					{
						$this->db->insertUser($account);
					}
					catch (Exception $e)
					{
						$this->view(parent::http401ResponseAction());
						return;
					}
				}
				else
				{
					$this->view(parent::http403ResponseAction());
					return;
				}
				
				$this->view(new ActionResult("auth", "login"));
			}
			else
			{
				$this->view($this->http405ResponseAction());
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
				if ($accountId == null)
				{
					$this->view($this->http401ResponseAction());
					return;
				}
				
				
				// Make sure manager exists in database
				$user = $this->db->findUserWithId($accountId);
				
				if ($user == null)
				{
					$this->view($this->http405ResponseAction());
					return;
				}
				
				$model = new EditUserModel($user, $state);
				
				// Return view
				$this->view(new ActionResult('auth', 'edit-account', $model));
			}
			else {
				// Make sure user id and the user itself exists in the database
				$post = new ArrayWrapper($_POST);
				
				$id = $post->getValueWithKey('id');
				if ($id == null) {
					$this->view($this->http405ResponseAction());
					return;
				}
				
				$user = $this->db->findUserWithId($id);
				if ($user == null)
				{
					$this->view($this->http405ResponseAction());
					return;
				}
				
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
		
		private function validateCredentials(LoginModel $credentials) : DbUserModel | null
		{
			// Check if user exists
			$user = $this->db->findUserWithEmailAddress($credentials->getEmail());
			if (empty($user))
				return null;
			
			if (password_verify($credentials->getPassword(), $user->getPassword()))
				return $user;
			else
				return null;
		}
	}