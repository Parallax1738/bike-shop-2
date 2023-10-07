<?php
	namespace bikeshop\app\controller;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\models\BikeDisplayModel;
	
	class BikesController extends Controller implements IHasIndexPage
	{
		public function index(array $params) : void
		{
			// Get Page Index and Result Count. If they are not found, set to defaults
			if (!array_key_exists('page', $_GET) || empty($_GET[ 'page' ]))
				$pageIndex = 0; else
				$pageIndex = $_GET[ 'page' ];
			
			if (!array_key_exists('results', $_GET) || empty($_GET[ 'results' ]))
				$resultCount = $_ENV[ '__DEFAULT_SEARCH_RESULT_COUNT' ] ?? 10; else
				$resultCount = $_GET[ 'results' ];
			
			// Connect to database to get data from it
			$db = new DatabaseConnector('user', 'password', 'BIKE_SHOP');
			try {
				// pageIndex * resultCount = amount of results the user has already viewed. Skip them.
				$bikes = $db->selectBikes($pageIndex * $resultCount, $resultCount);
				$pageCount = ceil($db->selectBikesCount("") / 10);
				echo $pageCount;
			} catch (Exception $e) {
				echo "shit: " . $e;
				return;
			}
			
			$model = new BikeDisplayModel($bikes, $pageCount, $pageIndex, $resultCount);
			$this->view('bikes', 'index', $model);
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}