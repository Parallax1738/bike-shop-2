<?php
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\database\models\DbUserModel;
	use bikeshop\app\models\EditUserModel;
	use bikeshop\app\models\StaffManagementModel;
	use Exception;
	
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
		
		/**
		 * @throws Exception
		 */
		public function editStaff(ApplicationState $state)
		{
			// Get Manager Id
			$get = new ArrayWrapper($_GET);
			$managerId = $get->getValueWithKey('m') ?? null;
			
			if ($managerId == null)
				throw new Exception("No Manager ID Provided");
			
			// Make sure manager exists in database
			$user = $this->db->findUserWithId($managerId);
			
			if ($user == null)
				throw new Exception("No manager found with provided ID " . $managerId);
			
			$model = new EditUserModel($user, $state);
			
			// Return view
			$this->view('sys-admin', 'edit-staff', $model);
		}
	}