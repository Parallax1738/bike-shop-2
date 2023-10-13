<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	
	class CreateAccountModel extends ModelBase
	{
		private string $username;
		private string $password;
		private int $userRoleId;
		
		private array $userRoles;
		
		public function __construct(string $username, string $password, ApplicationState | null $state, array $userRoles, int $userRoleId = 1)
		{
			$this->username = $username;
			$this->password = $password;
			$this->userRoleId = $userRoleId;
			$this->userRoles = $userRoles;
			parent::__construct($state);
		}
		
		public function getEmail() : string
		{
			return $this->username;
		}
		
		public function getPassword() : string
		{
			return $this->password;
		}
		
		public function getUserRoles() : array
		{
			return $this->userRoles;
		}
		
		public function getRoleId()
		{
			return $this->userRoleId;
		}
	}