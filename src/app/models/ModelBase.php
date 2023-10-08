<?php
	
	namespace bikeshop\app\models;
	class ModelBase
	{
		private string | null $jwtToken;
		
		public function __construct(string | null $jwtToken)
		{
			$this->jwtToken = $jwtToken;
		}
		
		public function getJwtToken() : string | null
		{
			return $this->jwtToken;
		}
	}