<?php
	
	namespace bikeshop\public\products;
	
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\database\entity\ProductEntity;
	use bikeshop\app\database\entity\ProductFilterEntity;
	use bikeshop\app\models\ProductsModel;
	
	/**
	 * Helper class to make the products/index.php page cleaner
	 */
	class ProductsPageHelper
	{
		public function __construct(private ProductsModel $data, private ArrayWrapper $get)
		{
		}
		
		/**
		 * Displays a header
		 * @return void
		 */
		public function displayHeader(): void
		{
			echo "<h1><b>" . $this->data->getProductDisplayName() . "</b></h1><br>";
		}
		
		/**
		 * Displays a search bar. Also  Displays a list of checkboxes, where each checkbox will represent a filter found
		 * inside the database. Its state is determined by $_GET, and it's ID is equal to "fil-{FILTER_ID}"
		 * @return void
		 */
		public function displaySearchBar(): void
		{
			$html = "<form method='GET' action='/products'>";
			
			// -- SEARCH BAR --
			$q = $this->get->getValueWithKey('q');
			$html .= "<label>Search: </label><input type='text' name='q' value='" . $q . "' placeholder='text-here' />";
			
			// -- CATEGORY LIST --
			$html .= "<ul>";
			foreach ($this->data->getProductsFilterList() as $filter)
			{
				if ($filter instanceof ProductFilterEntity)
				{
					$html .= $this->convertFilterIntoCheckbox($filter);
				}
			}
			$html .= "</ul>";
			
			// -- SUBMIT BUTTON --
			$html .= "<input type='submit' value='Search' style='color: blue; text-decoration: underline;' />";
			
			// -- FORM INPUTS --
			$chainer = new HiddenInputChainer($this->data, $this->get);
			$html .= $chainer
				->addCategory()
				->addCurrentPage()
				->addResultCount()
				->getHtml();
			
			$html .= "</form>";
			echo $html;
		}
		
		/**
		 * Displays all products from the products list. It wil also have a 'add to cart' and 'display more options'
		 * button assigned to each. Ensure to add a javascript function 'addToCart'
		 * @return void
		 */
		public function displayProductsList() : void
		{
			echo "<div>";
			foreach ($this->data->getList() as $item)
			{
				if ($item instanceof ProductEntity)
				{
					echo $this->displaySingleProduct($item);
				}
			}
			echo "</div>";
			
		}
		
		/**
		 * Displays a button that decrements the current page if it can
		 * @return void
		 */
		public function displayPreviousButton() : void
		{
			$html = "<form method='get' action='/products'>";
			if ($this->data->getCurrentPage() > 0)
			{
				// Display Left Arrow because if it is greater than 0, user can go back a page
				$newPage = $this->data->getCurrentPage() - 1;
				$html .= '<input type="submit" value="_<_" style="background-color: darkgrey" />';
				
				$chainer = new HiddenInputChainer($this->data, $this->get);
				$html .= $chainer
					->addCategory()
					->addResultCount()
					->addNewPage($newPage)
					->addFilters()
					->addQuery()
					->getHtml();
			}
			$html .= "</form>";
			echo $html;
		}
		
		/**
		 * Displays the page count {pageCount} / {maxPageCount}
		 * @return void
		 */
		public function displayPageCount() : void
		{
			echo "<div style='background-color: orange'>" . $this->data->getCurrentPage() + 1 . " / " . $this->data->getMaxPage();
		}
		
		/**
		 * Displays a button to increment the page if it can
		 * @return void
		 */
		public function displayNextButton() : void
		{
			$html = "<form method='get' action='/products'>";
			if ($this->data->getCurrentPage() < $this->data->getMaxPage() - 1)
			{
				// Display Left Arrow because if it is greater than 0, user can go back a page
				$newPage = $this->data->getCurrentPage() + 1;
				$html .= '<input type="submit" value="_>_" style="background-color: darkgrey" />';
				
				$chainer = new HiddenInputChainer($this->data, $this->get);
				$html .= $chainer
					->addCategory()
					->addResultCount()
					->addNewPage($newPage)
					->addFilters()
					->addQuery()
					->getHtml();
			}
			$html .= "</form>";
			echo $html;
		}
		
		/**
		 * Has a look at $_GET. If the filter was found inside it, it means the user has selected the checkbox and the
		 * function will add said checkbox automatically checked.
		 * @param ProductFilterEntity $filter
		 * @return string
		 */
		private function convertFilterIntoCheckbox(ProductFilterEntity $filter) : string
		{
			$html = "";
			$foundFilterId = "fil-" . $filter->getId();
			
			if ($filterChecked = $this->get->getValueWithKey($foundFilterId)) {
				// Filter found. If it is set to 'on', make it checked
				if ($filterChecked == 'on')
					$html .= $this->displaySingleFilterCheckBox(true, $foundFilterId, $filter->getName());
				else
					$html .= $this->displaySingleFilterCheckBox(false, $foundFilterId, $filter->getName());
			}
			else
				// Filter not found. Just default it to not checked
				$html .= $this->displaySingleFilterCheckBox(false, $foundFilterId, $filter->getName());
			
			return $html;
		}
		
		/**
		 * Displays a single checkbox for specific filter based on bellow parameters
		 * @param bool $checked If the default state for the checkbox should be 'checked'
		 * @param string $id The ID of the filter inside the database
		 * @param string $name The name of the filter
		 * @return string
		 */
		private function displaySingleFilterCheckBox(bool $checked, string $id, string $name) : string
		{
			if ($checked)
				return '<li><input onChange="this.form.submit()" name="'.$id.'" checked type="checkbox">'.$name.'<input/><l/>';
			else
				return '<li><input onChange="this.form.submit()" name="'.$id.'" type="checkbox">'.$name.'<input/><l/>';
		}
		
		/**
		 * Displays a single product.
		 * @param ProductEntity $product
		 * @return string
		 */
		private function displaySingleProduct(ProductEntity $product) : string
		{
			return '<p>' . $product->getName() . ' |
					<a style="color: blue; text-decoration: underline" href="/products/details?product=' . $product->getId() . '">More Details</a> |
					<a style="color: blue; text-decoration: underline">Add To Cart</a></p>
					
					<p>' . $product->getDescription() . '</p><br><hr><br>';
		}
	}