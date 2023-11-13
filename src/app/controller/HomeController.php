<?php
	
	namespace bikeshop\app\controller;
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\attributes\HttpMethod;
	use bikeshop\app\core\attributes\RouteAttribute;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	
	class HomeController extends Controller implements IHasIndexPage
	{
		#[RouteAttribute( HttpMethod::GET, "index" )]
		public function index(ApplicationState $state) : void
		{
			$this->view(new ActionResult('home', 'index', $state));
		}
		
		#[RouteAttribute( HttpMethod::GET, "test" )]
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}