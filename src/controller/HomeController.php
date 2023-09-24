<?php
	
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