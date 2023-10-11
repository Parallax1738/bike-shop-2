<?php
	
	namespace bikeshop\app\models;
	
	include 'ModelBase.php';
	
	class CreateAccountModel extends ModelBase
	{
		private string $username;
		private string $password;
		private int $userRoleId;
		
		public function __construct(string $username, string $password, int $userRoleId = 1)
		{
			$this->username = $username;
			$this->password = $password;
			$this->userRoleId = $userRoleId;
		}
		
		public function getEmail() : string
		{
			return $this->username;
		}
		
		public function getPassword() : string
		{
			return $this->password;
		}
		
		public function getRoleId()
		{
			return $this->userRoleId;
		}
	}