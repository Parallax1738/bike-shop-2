<?php
	
	namespace bikeshop\app\controller;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	
	class TestController extends Controller implements IHasIndexPage
	{
		public function index(ApplicationState $state) : void
		{
			echo '<h1>Hello World! (test controller)</h1>';
		}
		
		public function ohNo() : void
		{
			echo '<h1>oh fucking shit</h1>';
		}
	}