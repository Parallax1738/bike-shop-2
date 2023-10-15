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
		 * TODO - Move this to /auth/edit-account.
		 * @throws Exception
		 */
		public function editStaff(ApplicationState $state)
		{
			if ($_SERVER['REQUEST_METHOD'] == 'GET')
			{
				// Get Manager id
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
			else
			{
				// Make sure user id and the user itself exists in the database
				$post = new ArrayWrapper($_POST);
				
				$id = $post->getValueWithKey('id');
				if ($id == null) throw new Exception("Cannot edit user without an ID");
				
				$user = $this->db->findUserWithId($id);
				if ($user == null) throw new Exception("No user found with provided Id" . $id);
				
				# region Setting Values
				
				if ($post->keyExists('email'))
					$user->setEmailAddress($post->getValueWithKey('email'));
				if ($post->keyExists('first-name'))
					$user->setFirstName($post->getValueWithKey('first-name'));
				if ($post->keyExists('last-name'))
					$user->setLastName($post->getValueWithKey('last-name'));
				if ($post->keyExists('address'))
					$user->setAddress($post->getValueWithKey('address'));
				if ($post->keyExists('suburb'))
					$user->setSuburb($post->getValueWithKey('suburb'));
				if ($post->keyExists('state'))
					$user->setState($post->getValueWithKey('state'));
				if ($post->keyExists('postcode'))
					$user->setPostcode($post->getValueWithKey('postcode'));
				if ($post->keyExists('country'))
					$user->setCountry($post->getValueWithKey('country'));
				if ($post->keyExists('phone'))
					$user->setPhone($post->getValueWithKey('phone'));
				
				# endregion
				
				// If a new password has been provided, re-hash it and update it
				if ($post->keyExists('password'))
				{
					$newPass = password_hash($post->getValueWithKey('password'), PASSWORD_BCRYPT);
					$user->setPassword($newPass);
				}
				
				$this->db->updateUser($user);
			}
		}
	}