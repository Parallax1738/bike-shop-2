<?php
	
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\attributes\HttpMethod;
	use bikeshop\app\core\attributes\RouteAttribute;
	use bikeshop\app\core\Controller;
	
	class ErrorController extends Controller
	{
		#[RouteAttribute( HttpMethod::GET, "http404" )]
		public function http404() : void
		{
			$this->view(new ActionResult('error', 'http404'));
		}
		
		#[RouteAttribute( HttpMethod::GET, "http400" )]
		public function http400() : void
		{
			$this->view(new ActionResult('error', 'http400'));
		}
		
		#[RouteAttribute( HttpMethod::GET, "http401" )]
		public function http401() : void
		{
			$this->view(new ActionResult('error', 'http401'));
		}
		
		#[RouteAttribute( HttpMethod::GET, "http403" )]
		public function http403() : void
		{
			$this->view(new ActionResult('error', 'http403'));
		}
		
		#[RouteAttribute( HttpMethod::GET, "http405" )]
		public function http405() : void
		{
			$this->view(new ActionResult('error', 'http405'));
		}
	}