<?php
	
	namespace bikeshop\app\core;
	class Controller
	{
		/**
		 * Finds the view for the controller and action. Deprecated, please use Controller::view
		 * @param string $controller Controller name to look for. Example for `TestController` class would be `test`
		 * @param string $action The action or view you want to load
		 * @param mixed $data to be passed into the function. Optional
		 * @return void
		 * @deprecated Please use Controller::view
		 */
		protected function deprecatedView(string $controller, string $action, mixed $data = null) : void
		{
			$fileName = __DIR__ . "/../../public/" . $controller . "/" . $action . ".php";
			if (!file_exists($fileName)) {
				$this->viewError("The view {" . $fileName . "} does not exist. Please ensure that the view you are
				 trying to display is inside src/public/" . $controller . "/" . $action . ".php.");
			} else {
				// Loads the file, which is where the HTML/CSS will be loaded for the user
				include $fileName;
			}
		}
		
		/**
		 * Finds the view for the controller and action
		 * @param ActionResult $result Contains information about the view to load, and the model it needs
		 * @return void
		 */
		protected function view(ActionResult $result)
		{
			$fileName = $result->getViewFile(__DIR__ . "/../../public/");
			if (!file_exists($fileName))
			{
				$this->view($this->http404ResponseAction());
				return;
			}
			else
			{
				include $fileName;
			}
		}
		
		private function viewError($message) : void
		{
			echo '<h1 style="font-size: 2.5rem; font-weight: bold; color: red;">Error with views :)</h1>';
			echo '<p>' . $message . "</p>";
		}
		
		protected function http404ResponseAction(): ActionResult
		{
			return new ActionResult('error', '404');
		}
	}
