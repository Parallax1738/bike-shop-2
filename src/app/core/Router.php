<?php
	namespace bikeshop\app\core;
	use bikeshop\app\controller\AuthController;
	use bikeshop\app\controller\CartController;
	use bikeshop\app\controller\ErrorController;
	use bikeshop\app\controller\HomeController;
	use bikeshop\app\controller\ProductsController;
	use bikeshop\app\controller\ManagementController;
	use bikeshop\app\core\attributes\HttpMethod;
	use bikeshop\app\core\attributes\RouteAttribute;
	use Exception;
	use ReflectionClass;
	use ReflectionMethod;
	
	class Router
	{
		private Controller $indexController;
		private array $controllerMap;
		private ApplicationState $state;
		
		private array $routes;
		
		public function __construct(ApplicationState $state)
		{
			$this->state = $state;
			
			$homeController = new HomeController();
			$productsController = new ProductsController();
			
			$this->indexController = $homeController;
			$this->controllerMap[ "home" ] = $homeController;
			$this->controllerMap[ "auth" ] = new AuthController();
			$this->controllerMap[ "management" ] = new ManagementController();
			$this->controllerMap[ "error" ] = new ErrorController();
			$this->controllerMap[ "cart" ] = new CartController();
			
			// Products
			$this->controllerMap[ "products" ] = new ProductsController();
			
		}
		
		public function manageUrl() : void
		{
			$uri = $this->getUri();
			
			// If no controller, go to /
			if (empty($uri->getControllerName()))
			{
				$this->displayIndex($this->state);
				return;
			}
			
			// Otherwise, get controller and go to /{controllerName}
			$c = $this->getControllerFromStr($uri->getControllerName());
			if (is_null($c))
			{
				include(__DIR__ . "/../../public/error/http404.php");
				return;
			}
			
			// If action does not exist, but controller has index page, load it
			if (empty($uri->getActionName()) && $c instanceof IHasIndexPage)
			{
				$c->index($this->state);
				return;
			}
			
			// Action provided from user, parse it and get a callable from it
			$actionCallable = $this->getActionFromStr(
				$c,
				$uri->getActionName(),
				$uri->getHttpMethod());
			
			if ($actionCallable == null || !is_callable($actionCallable))
			{
				// Nothing was found, therefore error
				include(__DIR__ . "/../../public/error/http404.php");
				return;
			}
			
			call_user_func_array($actionCallable, [ $this->state ]);
		}
		
		private function displayIndex(ApplicationState $state) : void
		{
			try
			{
				if ($this->indexController instanceof IHasIndexPage)
				{
					$this->indexController->index($state);
				}
				else
				{
					include(__DIR__ . "/../../public/error/http404.php");
				}
			}
			catch (Exception $exception)
			{
				include(__DIR__ . "/../../public/error/http404.php");
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
			
			return new MvcUri($controller, $action, $this->stringToHttpMethod($_SERVER['REQUEST_METHOD']));
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
		
		/**
		 * Finds an action method from inside a controller based on an action name and HTTP method. It finds it
		 * looking at the attribute on top of a method.
	  	 * @param Controller $controller Where to find the action name inside of
		 * @param string $actionName The action name to search for on the attribute.
		 * @param HttpMethod $httpMethod The method type that the attribute should have
		 * @return array|null The method that you can call. THe array is a callable by the way, so you'll have to use
		 * the function call_usr_array(getActionFromStr(...));
		 */
		private function getActionFromStr(Controller $controller, string $actionName, HttpMethod $httpMethod) : array | null
		{
			if (empty($actionName))
			{
				return null;
			}
			
			$reflectedController = new ReflectionClass($controller);
			foreach ($reflectedController->getMethods() as $controllerMethod)
			{
				$method = $this->checkMethodAttributeAgainstAction($controllerMethod, $actionName, $httpMethod);
				if ($method)
					return [ $controller, $method->getName() ];
			}
			return null;
		}
		
		/**
		 * Loops through all attributes on the function. If an attribute with the 'RouteAttribute' one exists, and it's
		 * route name is equal to $targetAction and it's method type is equal to $methodType, then it returns the method
		 * . Otherwise it will return null
		 * @param ReflectionMethod $method Method to search
		 * @param string $targetAction Action to search for
		 * @param HttpMethod $targetMethod Method to search for
		 * @return ReflectionMethod | null If the method exists that match the following requirements, it will return
		 * the reflection method. Otherwise, it will return null.
		 */
		private function checkMethodAttributeAgainstAction(ReflectionMethod $method, string $targetAction, HttpMethod $targetMethod) : ReflectionMethod | null
		{
			foreach ($method->getAttributes() as $attribute)
			{
				$att = $attribute->newInstance();
				if ($att instanceof RouteAttribute)
				{
					$methodEqual = strcmp($att->getMethod()->name, $targetMethod->name) == 0;
					$actionNameEqual = strcmp($att->getAction(), $targetAction) == 0;
					if ($methodEqual && $actionNameEqual)
					{
						return $method;
					}
				}
			}
			return null;
		}
		
		private function stringToHttpMethod($method): HttpMethod
		{
			return match ( $method ) {
				"POST" => HttpMethod::POST,
				"PUT" => HttpMethod::PUT,
				"PATCH" => HttpMethod::PATCH,
				"DELETE" => HttpMethod::DELETE,
				default => HttpMethod::GET,
			};
		}
	}