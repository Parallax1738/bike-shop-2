<?php
	
	namespace bikeshop\app\core;
	
	use bikeshop\app\database\models\DbUserModel;
	
	/**
	 * Includes data about user account stuff, and eventually what URLs they have visited
	 */
	class ApplicationState
	{
		public function __construct(
			private DbUserModel $user
		)
		{
		
		}
		
		public function getUser(): DbUserModel
		{
			return $this->user;
		}
	}