<?php
	namespace bikeshop\app\controller;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
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
			$get = new ArrayWrapper($_GET);
			$currentPage = $get->getValueWithKey('page') ?? 0;
			$resultCount = $get->getValueWithKey('results') ?? $_ENV['__DEFAULT_SEARCH_RESULT_COUNT'];
			
			// Connect to database to get data from it
			$db = new DatabaseConnector('user', 'password', 'BIKE_SHOP');
			try
			{
				// pageIndex * resultCount = amount of results the user has already viewed. Skip them.
				$bikes = $db->selectProducts($this->productId, $currentPage * $resultCount, $resultCount);
				$maxPages = ceil($db->selectProductCount($this->productId) / $resultCount);
			}
			catch (Exception $e)
			{
				echo "Database Exception: " . $e;
				return;
			}
			
			$model = new PagingModel($bikes, $currentPage, $maxPages, $resultCount, $state);
			$this->deprecatedView('bikes', 'index', $model);
		}
		
		public function test() : void
		{
			echo "<h1>Test</h1>";
		}
	}