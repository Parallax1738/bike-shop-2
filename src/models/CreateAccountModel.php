<?php
	
	include 'ModelBase.php';
	
	class CreateAccountModel extends ModelBase
	{
		private string $username;
		private string $password;
		
		public function __construct(string $username, string $password)
		{
			$this->username = $username;
			$this->password = $password;
		}
		
		public function getEmail(): string
		{
			return $this->username;
		}
		
		public function getPassword(): string
		{
			return $this->password;
		}
	}