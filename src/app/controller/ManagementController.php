<?php
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\core\attributes\HttpMethod;
	use bikeshop\app\core\attributes\RouteAttribute;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\database\entity\UserEntity;
	use bikeshop\app\database\repository\UserRepository;
	use bikeshop\app\models\EditUserModel;
	use bikeshop\app\models\StaffManagementModel;
	use Exception;
	
	class ManagementController extends Controller implements IHasIndexPage
	{
		private DatabaseConnector $db;
		
		public function __construct()
		{
			$this->db = new UserRepository();
		}
		
		#[RouteAttribute(HttpMethod::GET, "index")]
		public function index(ApplicationState $state) : void
		{
			// Get all staff members, and managers
			$staffMembers = $this->db->selectAllUsers(2);
			$managers = $this->db->selectAllUsers(3);
			
			$data = new StaffManagementModel($staffMembers, $managers, $state);
			
			$this->view(new ActionResult('management', 'index', $data));
		}
		
		#[RouteAttribute(HttpMethod::GET, "roster")]
		public function roster(ApplicationState $state) : void
		{
			
			
			$this->view(new ActionResult('management', 'roster'));
		}
	}