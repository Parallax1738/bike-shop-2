<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\database\models\DbUserModel;
	
	class EditUserModel extends ModelBase
	{
		public function __construct(private DbUserModel $user, ApplicationState $state)
		{
			parent::__construct($state);
		}
		
		public function getUserModel(): DbUserModel
		{
			return $this->user;
		}
	}