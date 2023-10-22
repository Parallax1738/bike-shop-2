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
	
	class SearchController extends Controller implements IHasIndexPage
	{
		private DatabaseConnector $db;
		
		public function __construct()
		{
			$this->db = new DatabaseConnector();
		}
		
		public function index(ApplicationState $state) : void
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
					$this->view(new ActionResult('home', 'index'));
					return;
				}
				
				$products = $this->db->selectProducts(null, $filters, $currentPage * $resultCount, $resultCount);
				$maxPages = ceil($this->db->selectProductCount(null) / $resultCount);
				
				$model = new SearchModel($query, $products, $currentPage, $maxPages, $resultCount, $state);
				$this->view(new ActionResult('search', 'index', $model));
			}
			else
			{
				$this->view($this->http405ResponseAction());
			}
		}
	}