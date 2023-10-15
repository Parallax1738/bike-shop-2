<?php
	namespace bikeshop\app\core;
	
	class ActionResult
	{
		public function __construct(
			private string $controller,
			private string $action,
			private mixed $data = null
		) { }
		
		/**
		 * Finds the view asociated with the controller and action
		 * @param string $viewDir The directory where all the view php files are located. In this project, its /src/public
		 * @return string The view file for this specific action result
		 */
		public function getViewFile(string $viewDir)
		{
			$processedViewDir = $viewDir;
			
			// If the $viewDir parameter does not have '/', add it
			if ($viewDir[strlen($viewDir)] != '/')
				$processedViewDir .= '/';
			return $processedViewDir . $this->getController() . '/' . $this->getAction() . '.php';
		}
		
		public function getController(): string
		{
			return $this->controller;
		}
		
		public function getAction(): string
		{
			return $this->action;
		}
		
		public function getData(): mixed
		{
			return $this->data;
		}
	}