<?php
	namespace bikeshop\app\controller;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\models\PagingModel;
	use Exception;
	
	class ProductsController extends Controller implements IHasIndexPage
	{
		
		public function __construct(private int $productId)
		{
		
		}
		
		public function index(ApplicationState $state) : void
		{
			// Get Page Index and Result Count. If they are not found, set to defaults
			if (!array_key_exists('page', $_GET) || empty($_GET[ 'page' ]))
				$currentPage = 0;
			else
				$currentPage = $_GET[ 'page' ];
			
			if (!array_key_exists('results', $_GET) || empty($_GET[ 'results' ]))
				$resultCount = $_ENV['__DEFAULT_SEARCH_RESULT_COUNT'];
			else
				$resultCount = $_GET['results'];
			
			// Connect to database to get data from it
			$db = new DatabaseConnector('user', 'password', 'BIKE_SHOP');
			try {
				// pageIndex * resultCount = amount of results the user has already viewed. Skip them.
				$bikes = $db->selectProducts($this->productId, $currentPage * $resultCount, $resultCount);
				$maxPages = ceil($db->selectProductCount($this->productId) / $resultCount);
			} catch (Exception $e) {
				echo "shit: " . $e;
				return;
			}
			
			$model = new PagingModel($bikes, $currentPage, $maxPages, $resultCount, $state);
			$this->view('bikes', 'index', $model);
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}