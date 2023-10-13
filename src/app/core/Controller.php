<?php
	
	namespace bikeshop\app\core;
	class Controller
	{
		
		protected function view(string $controller, string $action, $data = null) : void
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
		
		private function viewError($message) : void
		{
			echo '<h1 style="font-size: 2.5rem; font-weight: bold; color: red;">Error with views :)</h1>';
			echo '<p>' . $message . "</p>";
		}
	}
