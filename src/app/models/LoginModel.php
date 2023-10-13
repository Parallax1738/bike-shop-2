<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	
	/**
	 * Contains information needed to log in a user
	 */
	class LoginModel extends ModelBase
	{
		private string $email;
		private string $password;
		
		public function __construct(string $email, string $password, ApplicationState $state)
		{
			$this->email = $email;
			$this->password = $password;
			parent::__construct($state);
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