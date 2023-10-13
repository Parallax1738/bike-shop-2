<?php
	namespace bikeshop\app\core;
	use bikeshop\app\controller\AuthController;
	use bikeshop\app\controller\ProductsController;
	use bikeshop\app\controller\HomeController;
	use bikeshop\app\controller\SysAdminController;
	use bikeshop\app\controller\TestController;
	use Exception;
	use ReflectionClass;
	
	class Router
	{
		private Controller $indexController;
		private array $controllerMap;
		private ApplicationState $state;
		
		public function __construct(ApplicationState $state)
		{
			$this->state = $state;
			
			$homeController = new HomeController();
			$this->indexController = $homeController;
			$this->controllerMap[ "home" ] = $homeController;
			$this->controllerMap[ "auth" ] = new AuthController();
			$this->controllerMap[ "sys-admin" ] = new SysAdminController();
			
			// Products
			$this->controllerMap[ "bikes" ] = new ProductsController(1);
			$this->controllerMap[ "scooters" ] = new ProductsController(2);
			$this->controllerMap[ "accessories" ] = new ProductsController(3);
			$this->controllerMap[ "apparel" ] = new ProductsController(4);
			$this->controllerMap[ "components" ] = new ProductsController(5);
			
		}
		
		public function manageUrl() : void
		{
			$uri = $this->getUri();
			
			// If no controller, go to /
			if (empty($uri->getControllerName())) {
				$this->displayIndex($this->state);
				return;
			}
			
			// Otherwise, get controller and go to /{controllerName}
			$c = $this->getControllerFromStr($uri->getControllerName());
			if (is_null($c)) {
				$this->notFound("app\core\Controller could not be found");
				return;
			}
			
			$a = $this->getActionFromStr($c, $uri->getActionName());
			if (empty($a)) {
				// No action, go to index page if it exists
				if ($c instanceof IHasIndexPage) {
					$c->index($this->state);
				} else {
					$this->notFound("Page not found");
				}
				return;
			}
			
			// Calling the action as a method inside the controller
			$c->$a($this->state);
		}
		
		private function displayIndex(ApplicationState $state) : void
		{
			try {
				if ($this->indexController instanceof IHasIndexPage) {
					$this->indexController->index($state);
				} else {
					$this->notFound("No index page found");
				}
			} catch (Exception $exception) {
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
			
			// Split the URL by '/'
			$parts = explode('/', trim($url, '/'));
			
			// Extract the controller and action
			$controller = $parts[ 0 ] ?? 'home';
			$action = $parts[ 1 ] ?? '';
			
			// Ensure to disclude parameters (grabbed using $_GET)
			if (str_contains($controller, '?'))
				$controller = $this->removeParams($controller);
			
			if (str_contains($action, '?'))
				$action = $this->removeParams($action);
			
			return new MvcUri($controller, $action, []);
		}
		
		/**
		 * Removes all the parameters from a url. Example: removeParams("controller?testParam=3") = "testParam"
		 * @param string $str String to parse
		 * @return string The fixed string
		 */
		private function removeParams(string $str) : string
		{
			return explode('?', $str)[ 0 ];
		}
		
		private function notFound($message) : void
		{
			echo '<div style="display: flex; height: 100vh; justify-content: center; align-items: center; flex-direction: column;">';
			echo '<h1 style="color: red !important; font-size: 3rem; font-weight: bold;">404 Not Found 😭</h1>';
			echo '<p><b>Message: </b>' . $message . '</p>';
			echo '</div>';
		}
		
		private function serverError($message) : void
		{
			echo '<div style="display: flex; height: 100vh; justify-content: center; align-items: center; flex-direction: column;">';
			echo '<h1 style="color: red !important; font-size: 3rem; font-weight: bold;">Server Error 😭</h1>';
			echo '<p><b>Message: </b>' . $message . '</p>';
			echo '</div>';
		}
		
		private function getControllerFromStr($controllerName) : Controller | null
		{
			if (array_key_exists(strTolower($controllerName), $this->controllerMap)) {
				return $this->controllerMap[ strtolower($controllerName) ];
			} else {
				return null;
			}
		}
		
		private function getActionFromStr(Controller $controller, string $actionName) : string | null
		{
			if (empty($actionName)) {
				return null;
			}
			
			$reflectedController = new ReflectionClass($controller);
			foreach ($reflectedController->getMethods() as $controllerMethod) {
				if (str_contains(strtolower($controllerMethod->getName()), strtolower($actionName))) {
					return $controllerMethod->getName();
				}
			}
			return null;
		}
	}