<?php
	require_once 'Controller.php';
	require_once 'IHasIndexPage.php';
	require_once '../controller/HomeController.php';
	require_once '../controller/TestController.php';
	require_once '../controller/BikesController.php';
	require_once '../controller/AuthController.php';
	
	class Router
	{
		private Controller $indexController;
		private array $controllerMap;
		
		public function __construct()
		{
			$homeController = new HomeController();
			
			$this->indexController = $homeController;
			$this->controllerMap[ "home" ] = $homeController;
			$this->controllerMap[ "test" ] = new TestController();
			$this->controllerMap[ "bikes" ] = new BikesController();
			$this->controllerMap[ "auth" ] = new AuthController();
		}
		
		public function manageUrl() : void
		{
			$uri = $this->getUri();
			
			// If no controller, go to /
			if (empty($uri->getControllerName()))
			{
				$this->displayIndex($uri->getParametersArray());
				return;
			}
			
			// Otherwise, get controller and go to /{controllerName}
			$c = $this->getControllerFromStr($uri->getControllerName());
			if (is_null($c))
			{
				$this->notFound("Controller could not be found");
				return;
			}
			
			$a = $this->getActionFromStr($c, $uri->getActionName());
			if (empty($a))
			{
				// No action, go to index page if it exists
				if ($c instanceof IHasIndexPage)
				{
					$c->index([]);
				}
				else
				{
					$this->notFound("Page not found");
				}
				return;
			}
			
			// Calling the action as a method inside the controller
			$c->$a($uri->getParametersArray());
		}
		
		private function displayIndex(array $params) : void
		{
			try
			{
				if ($this->indexController instanceof IHasIndexPage)
				{
					$this->indexController->index($params);
				}
				else
				{
					$this->notFound("No index page found");
				}
			}
			catch (Exception $exception)
			{
				$this->notFound("For fucks sake the home controller doesn't have an index method: " . $exception);
			}
		}
		
		/**
		 * Loop through URL's string. Add all characters to $str. When / is found, save that string to find get
		 * the controller/action. Once ? is found, add to parameters array. Therefore, we should loop through
		 * the string's characters and break every time we find a / or a ?. This is a fucking awful 'solution'
		 * @return MvcUri The Uri as an object to get whatever the fuck the user entered as an object
		 */
		private function getUri() : MvcUri
		{
			$url = $_SERVER[ "REQUEST_URI" ];
			
			$controller = "";
			$action = "";
			$params = [];
			$tempString = "";
			
			// | http://example.com | OR | http://example.com/ |
			if ($url == "" || $url == "/")
			{
				return new MvcUri($controller, $action, $params);
			}
			
			$urlSplit = mb_str_split($url);
			$urlSplit = array_slice($urlSplit, 1); // Get rid of the first '/' to not break things :(
			
			foreach ($urlSplit as $char)
			{
				// If neither controller nor action is empty and there is a /, then add it to controller or action
				if (!empty($controller) && !empty($action))
				{
					// do parameters
				}
				else if ($char == "/")
				{
					// If the controller is empty, then we know that it isn't set, and that we must do it now.
					if (empty($controller)) {
						$controller = $tempString;
						$tempString = "";
					}
				}
				else if ($char == "?" && empty($action))
				{
					// The action usually becomes before parameters via '?', for example:
					// http://example.com/controller/"action?test=5"
					$action = $tempString;
					$tempString = "";
				}
				else
				{
					$tempString .= $char;
				}
			}
			if (empty($controller))
			{
				$controller = $tempString;
			}
			else if (empty($action))
			{
				$action = $tempString;
			}
			
			return new MvcUri($controller, $action, $params);
		}
		
		private function notFound($message) : void
		{
			echo '<h1 style="color: red !important; font-size: 3rem; font-weight: bold;">Not Found :(</h1>';
			echo '<p><b>Message: </b>' . $message . '</p>';
		}
		
		private function serverError($message) : void
		{
			echo '<h1 style="color: red !important; font-size: 3rem; font-weight: bold;">Server Error :(</h1>';
			echo '<p><b>Message: </b>' . $message . '</p>';
		}
		
		private function getControllerFromStr($controllerName) : Controller | null
		{
			if (array_key_exists(strTolower($controllerName), $this->controllerMap))
			{
				return $this->controllerMap[ strtolower($controllerName) ];
			}
			else
			{
				return null;
			}
		}
		
		private function getActionFromStr(Controller $controller, string $actionName) : string | null
		{
			if (empty($actionName))
			{
				return null;
			}
			
			$reflectedController = new ReflectionClass($controller);
			foreach ($reflectedController->getMethods() as $controllerMethod)
			{
				if (str_contains(strtolower($controllerMethod->getName()), strtolower($actionName)))
				{
					return $controllerMethod->getName();
				}
			}
			return null;
		}
	}