<?php
	
	namespace bikeshop\app\core;
	class MvcUri
	{
		private string $controller;
		private string $action;
		private array $parameters;
		
		public function __construct(string $controller, string $action, array $parameters)
		{
			$this->controller = $controller;
			$this->action = $action;
			$this->parameters = $parameters;
		}
		
		public function getControllerName() : string
		{
			return $this->controller;
		}
		
		public function getActionName() : string
		{
			return str_replace('-', '', $this->action);
		}
		
		public function getParametersArray() : array
		{
			return $this->parameters;
		}
	}