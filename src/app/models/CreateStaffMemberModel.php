<?php
	
	namespace bikeshop\app\models;
	
	class CreateStaffMemberModel
	{
		public function __construct(
			private array $userRoleIdsAvailable
		)
		{
		
		}
		
		public function getAvailableUserRoleIds(): array
		{
			return $this->userRoleIdsAvailable;
		}
	}