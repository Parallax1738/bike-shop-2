<?php
	
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	
	class AboutController extends Controller implements IHasIndexPage
	{
		public function index(ApplicationState $state) : void
		{
			$this->view(new ActionResult('about', 'index'));
		}
	}