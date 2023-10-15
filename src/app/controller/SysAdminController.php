<?php
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\models\StaffManagementModel;
	
	class SysAdminController extends Controller implements IHasIndexPage
	{
		private DatabaseConnector $db;
		
		public function __construct()
		{
			$this->db = new DatabaseConnector("user", "password", "BIKE_SHOP");
		}
		
		public function index(ApplicationState $state)
		{
			$this->view('sys-admin', 'index');
		}
		
		public function staffManagement(ApplicationState $state)
		{
			// Get all staff members, and managers
			$staffMembers = $this->db->selectAllUsers(2);
			$managers = $this->db->selectAllUsers(3);
			
			$data = new StaffManagementModel($staffMembers, $managers, $state);
			
			$this->view('sys-admin', 'staff-management', $data);
		}
	}