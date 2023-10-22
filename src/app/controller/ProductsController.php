<?php
	namespace bikeshop\app\controller;
	use ArrayObject;
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\database\models\DbProductFilter;
	use bikeshop\app\models\PagingModel;
	use bikeshop\app\models\ProductsViewModel;
	use Exception;
	
	class ProductsController extends Controller implements IHasIndexPage
	{
		private DatabaseConnector $db;
		
		public function __construct()
		{
			$this->db = new DatabaseConnector();
		}
		
		public function index(ApplicationState $state) : void
		{
			if ($_SERVER[ 'REQUEST_METHOD' ] == 'GET') {
				// Get Page Index and Result Count. If they are not found, set to defaults
				$get = new ArrayWrapper($_GET);
				$currentPage = $get->getValueWithKey('page') ?? 0;
				$resultCount = $get->getValueWithKey('results') ?? $_ENV[ '__DEFAULT_SEARCH_RESULT_COUNT' ];
				$categoryId = $get->getValueWithKey('category') ?? null;
				
				// Connect to database to get data from it
				$db = new DatabaseConnector('user', 'password', 'BIKE_SHOP');
				
				// Get filters. Using the filter ids, convert them into fil-1, fil-2, etc...
				// If they exist in the _GET array, then add them into the $db->selectProducts call
				$allProductFilterList = $db->selectFiltersFromProductsQuery($categoryId, $currentPage * $resultCount, $resultCount);
				$userSelectedFilters = [];
				foreach ($allProductFilterList as $p) {
					if ($p instanceof DbProductFilter && $get->keyExists("fil-" . $p->getId())) {
						$userSelectedFilters[] = $p;
					}
				}
				
				// pageIndex * resultCount = amount of results the user has already viewed. Skip them.
				$bikes = $db->selectProducts($categoryId, $userSelectedFilters, $currentPage * $resultCount, $resultCount);
				$maxPages = ceil($db->selectProductCount($categoryId) / $resultCount);
				
				$pageName = $categoryId
					? $db->getCategoryName($categoryId)
					: "Products";
				
				$model = new ProductsViewModel($pageName, $allProductFilterList, $bikes, $currentPage, $maxPages, $resultCount, $state);
				$this->view(new ActionResult('products', 'index', $model));
			} else {
				$this->view($this->http405ResponseAction());
			}
		}
		
		public function search(ApplicationState $state) : void
		{
			if ($_SERVER["REQUEST_METHOD"] == "GET")
			{
				// Get data requried for queries and such
				$get = new ArrayWrapper($_GET);
				$query = $get->getValueWithKey('q');
				$filters = $get->getValueWithKey('filters') ?? [ ];
				$currentPage = $get->getValueWithKey('page') ?? 0;
				$resultCount = $get->getValueWithKey('results') ?? $_ENV['__DEFAULT_SEARCH_RESULT_COUNT'];
				
				// Make sure query exists
				if (empty($query))
				{
					$this->view(new ActionResult('products', 'search'));
					return;
				}
				
				$products = $this->db->selectProductsWithQuery($query, null, $filters, $currentPage * $resultCount, $resultCount);
				$maxPages = ceil($this->db->selectProductCountWithQuery($query, null, $filters) / $resultCount);
				$model = new SearchModel($query, $products, $currentPage, $maxPages, $resultCount, $state);
				$this->view(new ActionResult('products', 'search', $model));
			}
			else
			{
				$this->view($this->http405ResponseAction());
			}
		}
	}