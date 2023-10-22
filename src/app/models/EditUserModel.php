<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\database\entity\UserEntity;
	
	class EditUserModel extends ModelBase
	{
		public function __construct(private UserEntity $user, ApplicationState $state)
		{
			parent::__construct($state);
		}
		
		public function getUserModel(): UserEntity
		{
			return $this->user;
		}
	}