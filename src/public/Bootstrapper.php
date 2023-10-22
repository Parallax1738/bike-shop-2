<?php
	namespace bikeshop\public;
	require "../../vendor/autoload.php";
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\authentication\TokenManager;
	use bikeshop\app\core\Router;
	use bikeshop\app\database\entities\UserEntity;
	use Exception;
	
	/**
	 * Manages the initialisation of the application
	 */
	class Bootstrapper
	{
		public function __construct()
		{
		
		}
		
		/**
		 * Initialises authentication for the application; it firstly creates a new authManager instance, and detects
		 * if the user has logged in or not
		 */
		public function InitAuth(): null | UserEntity
		{
			// TODO - Move this into AuthManager, and rename it to AuthService
			$manager = new TokenManager();
			$loggedInUser = null;
			
			if (array_key_exists('token', $_COOKIE) && !empty($_COOKIE['token']))
			{
				// The user has a token. Verify it
				try
				{
					$loggedInUser = $manager->verifyToken($_COOKIE['token']);
				}
				catch (Exception $e)
				{
					echo $e->getMessage();
				}
			}
			
			return $loggedInUser;
		}
		
		/**
		 * Starts the program fully by calling the Router to get the controller/action to be viewed to the user
		 */
		public function Start(ApplicationState $state): void
		{
			$router = new Router($state);
			$router->manageUrl();
		}
	}