<?php
require 'Controller.php';
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
    if (empty($uri->getController())) {
        $this->displayIndex();
        return;
    }
    
    // Otherwise, get controller and go to /{controllerName}
    $c = $this->getControllerFromStr($uri->getController());
    if (is_null($c)) {
        $this->notFound("Controller could not be found");
        return;
    }
    
//    $a = $this->getActionFromStr($c, $uri->getAction());
  }
  
  private function displayIndex(): void {
    try {
      $this->indexController->index();
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
  
  private function getControllerFromStr($controllerName): Controller | null {
    if (array_key_exists(strTolower($controllerName), $this->controllerMap)) {
        return $this->controllerMap[strtolower($controllerName)];
    } else {
        return null;
    }
  }
  
  private function getActionFromStr(Controller $controller, string $action) {
  
  }
}