<?php
	
	class BikesController extends Controller implements IHasIndexPage
	{
		public function index(array $params) : void
		{
			$db = new DatabaseConnector('user', 'password', 'BIKE_SHOP');
			$db->selectAllProducts(0, 100, "Bike", -1);
			$this->view('bikes', 'index', "[test data]");
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}