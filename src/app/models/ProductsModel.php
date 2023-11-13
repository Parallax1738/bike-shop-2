<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	
	class ProductsModel extends PagingModel
	{
		/**
		 * Model that displays a subset of products, and splits them into pages
		 * @param string $productDisplayName The product name to be displayed as a title, for example, "Bikes"
		 * @param string $productHtmlName The product name that is used in the URL, for example "localhost/**bikes**"
		 * @param array $list The subset of all products
		 * @param int $currentPage The current page
		 * @param int $pageCount The total page count
		 * @param int $maxResults The amount of products to be displayed
		 * @param ApplicationState $state The application state
		 */
		public function __construct(private string $productDisplayName, private array $productFilterList, array $list, int $currentPage, int $pageCount, int $maxResults, ApplicationState $state)
		{
			parent::__construct($list, $currentPage, $pageCount, $maxResults, $state);
		}
		
		public function getProductDisplayName() : string
		{
			return $this->productDisplayName;
		}
		
		public function getProductsFilterList() : array
		{
			return $this->productFilterList;
		}
	}