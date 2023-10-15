<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	
	class StaffManagementModel extends ModelBase
	{
		public function __construct(
			private array $staffMembers,
			private array $managers,
			ApplicationState $state
		)
		{
			parent::__construct($state);
		}
		
		public function getStaffMembers(): array
		{
			return $this->staffMembers;
		}
		
		public function getManagers(): array
		{
			return $this->managers;
		}
	}