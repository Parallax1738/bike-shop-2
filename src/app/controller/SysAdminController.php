<?php
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\models\CreateStaffMemberModel;
	
	class SysAdminController extends Controller implements IHasIndexPage
	{
		private DatabaseConnector $db;
		
		public function __construct()
		{
			$this->db = new DatabaseConnector("user", "password", "BIKE_SHOP");
		}
		
		public function index(array $params)
		{
			$this->view('sys-admin', 'index');
		}
		
		public function staffManagement()
		{
			$this->view('sys-admin', 'staff-management');
		}
		
		public function createStaffMember()
		{
			if ($_SERVER['REQUEST_METHOD'] == 'GET')
			{
				$data = new CreateStaffMemberModel($this->db->selectAllUserRoles());
				$this->view('sys-admin', 'createStaffMember', $data);
			}
			else if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				// get email and password
			}
		}
	}