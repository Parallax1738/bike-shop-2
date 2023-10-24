<?php
	
	namespace bikeshop\app\core\attributes;
	
	class RouteAttribute
	{
		public function __construct(private HttpMethod $method, private string $action)
		{ }
		
		public function getMethod() : HttpMethod
		{
			return $this->method;
		}
		
		public function getAction() : string
		{
			return $this->action;
		}
	}