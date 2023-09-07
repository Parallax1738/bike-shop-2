<?php
	
	class TestController extends Controller implements IHasIndexPage
	{
		public function index(array $params) : void
		{
			echo '<h1>Hello World! (test controller)</h1>';
		}
		
		public function ohNo() : void
		{
			echo '<h1>oh fucking shit</h1>';
		}
	}