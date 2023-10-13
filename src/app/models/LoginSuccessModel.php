<?php
	namespace bikeshop\app\models;
	
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\core\authentication\JwtToken;
	
	class LoginSuccessModel extends ModelBase
	{
		public function __construct(
			private JwtToken $token,
			ApplicationState $state
		)
		{
			parent::__construct($state);
		}
		
		public function getToken()
		{
			return $this->token;
		}
	}