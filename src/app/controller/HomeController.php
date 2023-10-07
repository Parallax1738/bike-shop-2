<?php
	
	namespace bikeshop\app\controller;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	
	class HomeController extends Controller implements IHasIndexPage
	{
		public function index(array $params) : void
		{
			$this->view('home', 'index', 10);
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}