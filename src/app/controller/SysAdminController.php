<?php
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\models\CreateStaffMemberModel;
	
	class SysAdminController extends Controller implements IHasIndexPage
	{
		public function __construct()
		{
			$this->db = new DatabaseConnector("user", "password", "BIKE_SHOP");
		}
		
		public function index(ApplicationState $state)
		{
			$this->view('sys-admin', 'index');
		}
		
		public function staffManagement()
		{
			$this->view('sys-admin', 'staff-management');
		}
	}