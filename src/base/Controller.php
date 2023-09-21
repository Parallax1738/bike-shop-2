<?php
	
	class Controller
	{
        protected function view(string $controller, string $action, $data): void
        {
            $fileName = __DIR__ . "/../view/" . $controller . "/" . $action . ".php";
            if (!file_exists($fileName)) {
                $this->viewError("The view does not exist, dumbass. Make sure you know how the fuck to type ");
            } else {
				// Loads the file, which is where the HTML/CSS will be loaded for the user
                require_once $fileName;
            }
        }

        private function viewError($message): void
        {
            echo '<h1 style="font-size: 2.5rem; font-weight: bold; color: red;">Error with views :)</h1>';
            echo '<p>' . $message . "</p>";
        }
	}
