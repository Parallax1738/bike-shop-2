<?php
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	
	class SysAdminController extends Controller implements IHasIndexPage
	{
		
		public function index(array $params)
		{
			$this->view('sys-admin', 'index');
		}
		
		public function staffManagement()
		{
			$this->view('sys-admin', 'staff-management');
		}
	}