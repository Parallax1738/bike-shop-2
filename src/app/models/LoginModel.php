<?php
	
	namespace bikeshop\app\models;
	
	/**
	 * Contains information needed to log in a user
	 */
	class LoginModel
	{
		private string $email;
		private string $password;
		
		public function __construct(string $email, string $password)
		{
			$this->email = $email;
			$this->password = $password;
		}
		
		public function getEmail() : string
		{
			return $this->email;
		}
		
		public function getPassword() : string
		{
			return $this->password;
		}
	}