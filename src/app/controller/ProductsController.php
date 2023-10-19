<?php
	namespace bikeshop\app\controller;
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\models\PagingModel;
	use bikeshop\app\models\ProductsViewModel;
	use Exception;
	
	class ProductsController extends Controller implements IHasIndexPage
	{
		
		public function __construct(private string $productName, private int | null $productId = null)
		{
		
		}
		
		public function index(ApplicationState $state) : void
		{
			if ($_SERVER['REQUEST_METHOD'] == 'GET')
			{
				// Get Page Index and Result Count. If they are not found, set to defaults
				$get = new ArrayWrapper($_GET);
				$currentPage = $get->getValueWithKey('page') ?? 0;
				$resultCount = $get->getValueWithKey('results') ?? $_ENV['__DEFAULT_SEARCH_RESULT_COUNT'];
				
				$query = $get->getValueWithKey('q');
				$filters = $get->getValueWithKey('filters');
				
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
				
				$model = new ProductsViewModel($this->productName, $this->productName, $bikes, $currentPage, $maxPages, $resultCount, $state);
				$this->view(new ActionResult('products', 'index', $model));
			}
			else
			{
				$this->view($this->http405ResponseAction());
			}
		}
	}