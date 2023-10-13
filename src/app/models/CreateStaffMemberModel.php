<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	
	class CreateStaffMemberModel extends ModelBase
	{
		public function __construct(
			private array $userRoleIdsAvailable,
			ApplicationState $state
		)
		{
			parent::__construct($state);
		}
		
		public function getAvailableUserRoleIds(): array
		{
			return $this->userRoleIdsAvailable;
		}
	}