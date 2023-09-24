<?php
	
	class BikesController extends Controller implements IHasIndexPage
	{
		public function index(array $params) : void
		{
			$this->view('bikes', 'index', "[test data]");
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}