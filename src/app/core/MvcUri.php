<?php
	
	namespace bikeshop\app\core;
	use bikeshop\app\core\attributes\HttpMethod;
	
	class MvcUri
	{
		
		public function __construct(private readonly string $controller, private readonly string $action, private readonly HttpMethod $httpMethod)
		{
		
		}
		
		public function getControllerName() : string
		{
			return $this->controller;
		}
		
		public function getActionName() : string
		{
			return str_replace('-', '', $this->action);
		}
		
		public function getHttpMethod() : HttpMethod
		{
			return $this->httpMethod;
		}
	}