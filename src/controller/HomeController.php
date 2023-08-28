<?php
	
	class HomeController extends Controller implements IHasIndexPage
	{
		public function index(array $params) : void
		{
			echo "<h1>Hello, World! (home controller)</h1>";
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}