<?php
	require_once '../database/models/DbProduct.php';
	require_once '../models/BikeDisplayModel.php';
	class BikesController extends Controller implements IHasIndexPage
	{
		public function index(array $params) : void
		{
			$db = new DatabaseConnector('user', 'password', 'BIKE_SHOP');
			try
			{
				$bikes = $db->selectBikes(0, 15);
			}
			catch (Exception $e)
			{
				echo "shit: " . $e;
				return;
			}

			$model = new BikeDisplayModel($bikes, 0, 10);
			$this->view('bikes', 'index', $model);
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}