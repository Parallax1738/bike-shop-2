<?php
	namespace bikeshop\app\controller;
	
	use bikeshop\app\core\ActionResult;
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\attributes\HttpMethod;
	use bikeshop\app\core\attributes\RouteAttribute;
	use bikeshop\app\core\Controller;
	use bikeshop\app\core\IHasIndexPage;
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\database\repository\UserRepository;
	use bikeshop\app\models\RosterModel;
	use bikeshop\app\models\StaffManagementModel;
	use DateInterval;
	use DateTime;
	
	class ManagementController extends Controller implements IHasIndexPage
	{
		private DatabaseConnector $db;
		
		public function __construct()
		{
			$this->db = new UserRepository();
		}
		
		#[RouteAttribute( HttpMethod::GET, "index" )]
		public function index(ApplicationState $state) : void
		{
			$staffMembers = $this->db->selectAllUsers(2);
			$managers = $this->db->selectAllUsers(3);
			
			$data = new StaffManagementModel($staffMembers, $managers, $state);
			
			$this->view(new ActionResult('management', 'index', $data));
		}
		
		#[RouteAttribute( HttpMethod::GET, "roster" )]
		public function roster(ApplicationState $state) : void
		{
			// Get start and end dates
			$start = new DateTime();
			$interval = DateInterval::createFromDateString('14 Days');
			$end = ( new DateTime() )->add($interval);
			
			$roster = $this->db->getRoster($start, $end);
			$data = new RosterModel($start, $end, $roster, $state);
			
			$this->view(new ActionResult('management', 'roster', $data));
		}
	}