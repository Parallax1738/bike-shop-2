<?php
	
	namespace bikeshop\app\core;
	
	use bikeshop\app\database\entity\UserEntity;
	
	/**
	 * Includes data about user account stuff, and eventually what URLs they have visited
	 */
	class ApplicationState
	{
		public function __construct(private UserEntity | null $user)
		{
		
		}
		
		public function getUser() : UserEntity | null
		{
			return $this->user;
		}
		
		public function setUser(UserEntity | null $user) : void
		{
			$this->user = $user;
		}
	}