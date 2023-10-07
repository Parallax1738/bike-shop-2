<?php
	namespace bikeshop\public;
	require "../../vendor/autoload.php";
	use bikeshop\app\core\AuthManager;
	use bikeshop\app\core\Router;
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
		public function InitAuth(): void
		{
			// TODO - Move this into AuthManager, and rename it to AuthService
			$manager = new AuthManager();
			$isLoggedIn = false;
			
			if (array_key_exists('token', $_COOKIE) && !empty($_COOKIE['token']))
			{
				// The user has a token. Verify it
				try {
					$isLoggedIn = $manager->verifyToken($_COOKIE['token']);
				} catch (Exception $e) {
					echo $e;
				}
			}
			
			if ($isLoggedIn) {
				echo "You are logged in!";
			} else {
			
			}
		}
		
		/**
		 * Starts the program fully by calling the Router to get the controller/action to be viewed to the user
		 */
		public function Start(): void
		{
			require_once '../app/core/AuthManager.php';
			
			$router = new Router();
			$router->manageUrl();
		}
	}