<?php
	require_once '../models/CreateAccountModel.php';
	class AuthController extends Controller
	{
		public function login()
		{
			$this->view('auth', 'login');
		}
		
		public function createAccount()
		{
			if (!$_SERVER["REQUEST_METHOD"] == "POST")
			{
				return;
			}
			
			$account = null;
			// get data
			try
			{
				$account = $this->getUserFromRequest();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
			
			if ($account instanceof CreateAccountModel)
			{
				// User is not null, insert it into the database
				$dbo = new DatabaseConnector("user", "password", "BIKE_SHOP");
				try
				{
					$dbo->insertUser($account);
				}
				catch (Exception $e)
				{
					echo "<p>An account with this email address already exists. Either log in or reset your password</p>";
				}
			}

//			$this->view('auth', 'create-account');
		}
		
		/**
		 * @throws Exception if a GET request was performed instead of a POST or if username/password fileds are wrong
		 */
		private function getUserFromRequest() : CreateAccountModel|null
		{
			// TODO - Instead of throwing exceptions, reroute to login() and show errors.
			// TODO - Rename 'emailAddress' to 'email' like a sensible person
			
			if ($_SERVER["REQUEST_METHOD"] != "POST")
			{
				throw new Exception("Cannot GET create-account, you can only POST");
			}
			
			$post = $_POST;
			
			// Check if emailAddress exists, and if it is of correct length
			if (!array_key_exists("email", $post)) throw new Exception("No emailAddress was provided");
			
			$emailAddress = $post["email"];
			
			if (empty($emailAddress) || strlen($emailAddress) > 50) throw new Exception("Username has invalid size. Must be between 1 - 50 letters long");
			$emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);
			
			// Check if password exists, and if it is of correct length
			if (!array_key_exists("password", $post)) throw new Exception("No password was provided");
			
			$password = $post["password"];
			
			// TODO - Password (and email) should have infinite string length (or at least 256)
			if (empty($password) || strlen($password) > 50) throw new Exception("Password has invalid size. Must be between 1 - 50 letters long");
			
			return new CreateAccountModel($emailAddress, $password);
		}
	}