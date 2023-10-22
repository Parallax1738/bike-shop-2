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
		
		public function __construct(private string $productName, private int | null $categoryId = null)
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
				
				// Connect to database to get data from it
				$db = new DatabaseConnector('user', 'password', 'BIKE_SHOP');
				
				// Get filters. Using the filter ids, convert them into fil-1, fil-2, etc...
				// If they exist in the _GET array, then add them into the $db->selectProducts call
				$allProductFilterList = $db->selectFiltersFromProductsQuery($this->categoryId, $currentPage * $resultCount, $resultCount);
				$userSelectedFilters = [];
				foreach ($allProductFilterList as $p)
				{
					if ($p instanceof DbProductFilter && $get->keyExists("fil-" . $p->getId()))
					{
						$userSelectedFilters[] = $p;
					}
				}
				
				// pageIndex * resultCount = amount of results the user has already viewed. Skip them.
				$bikes = $db->selectProducts($this->categoryId, $userSelectedFilters,$currentPage * $resultCount, $resultCount);
				$maxPages = ceil($db->selectProductCount($this->categoryId) / $resultCount);
				
				
				$model = new ProductsViewModel($this->productName, $this->productName, $allProductFilterList, $bikes, $currentPage, $maxPages, $resultCount, $state);
				$this->view(new ActionResult('products', 'index', $model));
			}
			else
			{
				$this->view($this->http405ResponseAction());
			}
		}
	}