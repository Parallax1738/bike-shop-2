<?php
	
	namespace bikeshop\app\models;
	
	class LoginModel extends ModelBase
	{
		private string $email;
		private string $password;
		
		public function __construct(string | null $jwt, string $email, string $password)
		{
			parent::__construct($jwt);
			
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