<?php
require 'Controller.php';
	require 'IHasIndexPage.php';
require '../controller/HomeController.php';
require '../controller/TestController.php';

class Router
{
	private Controller $indexController;
	private array $controllerMap;
	
	public function __construct()
	{
	    $homeController = new HomeController();
	
	    $this->indexController = $homeController;
	    $this->controllerMap["home"] = $homeController;
	    $this->controllerMap["test"] = new TestController();
	}
	
	public function manageUrl(MvcUri $uri): void
	{
	    // If no controller, go to /
	    if (empty($uri->getControllerName())) {
	        $this->displayIndex();
	        return;
	    }
	
	    // Otherwise, get controller and go to /{controllerName}
	    $c = $this->getControllerFromStr($uri->getControllerName());
	    if (is_null($c)) {
	        $this->notFound("Controller could not be found");
	        return;
	    }
	  
	    $a = $this->getActionFromStr($c, $uri->getActionName());
	    if (empty($a)) {
		    // No action, go to index page if it exists
		    if ($c instanceof IHasIndexPage) {
				$c->index([]);
			} else {
				$this->notFound("Page not found");
			}
			return;
		}
		
		// Calling the action as a method inside the controller
		$c->$a();
	}
	  
    private function displayIndex(): void {
    try {
	    if ($this->indexController instanceof IHasIndexPage) {
		    $this->indexController->index([]);
	    } else {
		    $this->notFound("No index page found");
	    }
    }
    catch (Exception $exception) {
      $this->notFound("For fucks sake the home controller doesn't have an index method: " . $exception);
    }
  }
		
	private function notFound($message): void
	{
        echo '<h1 style="color: red !important; font-size: 3rem; font-weight: bold;">Not Found :(</h1>';
        echo '<p><b>Message: </b>' . $message . '</p>';
    }
	
	private function serverError($message): void
	{
		echo '<h1 style="color: red !important; font-size: 3rem; font-weight: bold;">Server Error :(</h1>';
		echo '<p><b>Message: </b>' . $message . '</p>';
	}
  
    private function getControllerFromStr($controllerName): Controller | null {
	    if (array_key_exists(strTolower($controllerName), $this->controllerMap)) {
            return $this->controllerMap[strtolower($controllerName)];
	    } else {
            return null;
	    }
    }
		
	private function getActionFromStr(Controller $controller, string $actionName): string|null
	{
		if (empty($actionName)) {
			return null;
		}
		
		$reflectedController = new ReflectionClass($controller);
		foreach ($reflectedController->getMethods() as $controllerMethod) {
			if (str_contains($controllerMethod->getName(), $actionName)) {
				return $controllerMethod->getName();
			}
		}
		return null;
	}
}