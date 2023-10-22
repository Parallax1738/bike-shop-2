<?php
	
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	
	class SearchController extends Controller implements IHasIndexPage
	{
		
		public function index(ApplicationState $state) : void
		{
			if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
			
				$get = new ArrayWrapper($_GET);
				$query = $get->getValueWithKey('q');
				$filters = $get->getValueWithKey('filters');
				
				if (empty($query))
				{
					$this->view(new ActionResult('home', 'index'));
					return;
				}
			
			}
			else
			{
			}
		}
	}