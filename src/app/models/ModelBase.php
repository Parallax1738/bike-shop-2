<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	
	class ModelBase
	{
		public function __construct(private ApplicationState $state)
		{
		
		}
		
		public function getState(): ApplicationState
		{
			return $this->state;
		}
	}