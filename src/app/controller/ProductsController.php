<?php
	namespace bikeshop\app\controller;
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\core\attributes\HttpMethod;
	use bikeshop\app\core\attributes\RouteAttribute;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\entity\ProductFilterEntity;
	use bikeshop\app\database\repository\ProductRepository;
	use bikeshop\app\models\ProductsModel;
	
	class ProductsController extends Controller implements IHasIndexPage
	{
		private ProductRepository $db;
		
		public function __construct()
		{
			$this->db = new ProductRepository();
		}
		
		#[RouteAttribute( HttpMethod::GET, "index" )]
		public function index(ApplicationState $state) : void
		{
			// Get Page Index and Result Count. If they are not found, set to defaults
			$get = new ArrayWrapper($_GET);
			$currentPage = $get->getValueWithKey('page') ?? 0;
			$resultCount = $get->getValueWithKey('results') ?? $_ENV[ '__DEFAULT_SEARCH_RESULT_COUNT' ];
			$categoryId = $get->getValueWithKey('category') ?? null;
			$query = $get->getValueWithKey('q');
			
			// Get filters. Using the filter ids, convert them into fil-1, fil-2, etc...
			// If they exist in the _GET array, then add them into the $db->selectProducts call
			$allProductFilterList = $query ? $this->db->selectFiltersFromProducts($categoryId, $query) : $this->db->selectFiltersFromProducts($categoryId);
			
			$userSelectedFilters = [];
			foreach ($allProductFilterList as $p) {
				if ($p instanceof ProductFilterEntity && $get->keyExists("fil-" . $p->getId())) {
					$userSelectedFilters[] = $p;
				}
			}
			
			// pageIndex * resultCount = amount of results the user has already viewed. Skip them.
			$bikes = $query ? $this->db->selectProductsWithQuery($query, $categoryId, $userSelectedFilters, $currentPage * $resultCount, $resultCount) : $this->db->selectProducts($categoryId, $userSelectedFilters, $currentPage * $resultCount, $resultCount);
			$maxPages = ceil($this->db->selectProductCount($categoryId) / $resultCount);
			
			// If no category id was passed, just use Products
			$pageName = $categoryId ? $this->db->getCategoryName($categoryId) : "Products";
			
			$model = new ProductsModel($pageName, $allProductFilterList, $bikes, $currentPage, $maxPages, $resultCount, $state);
			$this->view(new ActionResult('products', 'index', $model));
		}
		
		#[RouteAttribute( HttpMethod::GET, "details" )]
		public function details() : void
		{
			$get = new ArrayWrapper($_GET);
			
			if (!( $productId = $get->getValueWithKey('product') )) {
				$this->view($this->http403ResponseAction());
				return;
			}
			
			$product = $this->db->selectProduct($productId);
			$this->view(new ActionResult('products', 'details', $product));
		}
	}