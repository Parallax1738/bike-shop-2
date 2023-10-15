<?php
	namespace bikeshop\app\core;
	
	class ActionResult
	{
		public function __construct(
			private string $controller,
			private string $action,
			private mixed $data
		) { }
		
		public function getController(): string
		{
			return $this->controller;
		}
		
		public function getAction(): string
		{
			return $this->action;
		}
		
		private function getData(): mixed
		{
			return $this->data;
		}
	}