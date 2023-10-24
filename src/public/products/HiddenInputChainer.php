<?php
	
	namespace bikeshop\public\products;
	
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\database\entity\ProductFilterEntity;
	use bikeshop\app\models\ProductsModel;
	
	/**
     * Helper class to chain inputs with simple methods. For example, you could do something as such:
	 * <code>
	 *     $chainer = new HiddenInputChainer($data, $get);
 	 *     $html = $chainer
	 * 				->addCurrentPage()
	 * 				->addCategory()
	 * 				->addResultCount()
	 * 				->getHtml();
	 *     echo $html;
	 * </code>
	 */
	class HiddenInputChainer
	{
		private string $html;
		
		public function __construct(private ProductsModel $data, private ArrayWrapper $get)
		{
			$this->html = "";
		}
		
		public function addCurrentPage() : HiddenInputChainer
		{
			if ($currentPage = $this->get->getValueWithKey('page'))
				$this->html .= "<input type='hidden' name='page' value='" . $currentPage . "' />";
			return $this;
		}
		
		public function addNewPage($newPage) : HiddenInputChainer
		{
			$this->html .= "<input type='hidden' name='page' value='" . $newPage . "' />";
			return $this;
		}
		
		public function addCategory() : HiddenInputChainer
		{
			if ($categoryId = $this->get->getValueWithKey('category'))
				$this->html .= "<input type='hidden' name='category' value='" . $categoryId . "' />";
			
			return $this;
		}
		
		public function addResultCount(): HiddenInputChainer
		{
			if ($results = $this->get->getValueWithKey('results'))
				$this->html .= "<input type='hidden' name='results' value='" . $results . "' />";
			return $this;
		}
		
		public function addFilters() : HiddenInputChainer
		{
			foreach ($this->data->getProductsFilterList() as $f)
			{
				if (!$f instanceof ProductFilterEntity)
					continue;
				
				// Remember that fil-4 means that the user selected the filter with an id of 4, passed as a param in
				// $_GET
				if ($selectedFilter = $this->get->getValueWithKey('fil-' . $f->getId()))
					$this->html .= "<input type='hidden' name='fil-".$f->getId()."' value='on' />";
			}
			return $this;
		}
		
		public function addQuery() : HiddenInputChainer
		{
			if ($q = $this->get->getValueWithKey('q'))
				$this->html .= "<input type='hidden' name='q' value='" . $q . "' />";
			
			return $this;
		}
		
		public function getHtml() : string
		{
			return $this->html;
		}
	}