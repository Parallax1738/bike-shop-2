<?php
	
	namespace bikeshop\app\controller;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	
	class HomeController extends Controller implements IHasIndexPage
	{
		public function index(ApplicationState $state) : void
		{
			$this->deprecatedView('home', 'index', $state);
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}