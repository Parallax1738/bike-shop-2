<?php
	
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	
	class ModelBase
	{
		public function __construct(private ApplicationState | null $state)
		{
		
		}
		
		public function getState(): ApplicationState | null
		{
			return $this->state;
		}
	}