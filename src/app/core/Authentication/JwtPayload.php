<?php
	namespace bikeshop\app\core\Authentication;
	use DateTime;
	
	/**
	 * This object contains all data that will be placed inside the JWT token.
	 */
	class JwtPayload
	{
		/**
		 * Issuer: The token issuer. In this project, it should be `bike-shop`
		 */
		private string $iss;
		
		/**
		 * Issued At: When the token was issued to the user
		 */
		private DateTime $iat;
		
		/**
		 * Expiry: When the token expires
		 */
		private DateTime $exp;
		
		/**
		 * An array of data that contains stuff about the user, like their address or something
		 */
		private array $data;
		
		/**
		 * @param string $iss Issuer: The token issuer. In this project, it should be bike-shop
		 * @param string $iat Issued At: When the token was issued to the user
		 * @param string $exp Expiry: When the token expires
		 * @param array $data An array of data that contains stuff about the user, like their address or something
		 */
		public function __construct(string $iss, DateTime $iat, DateTime $exp, int $userId)
		{
			$this->iss = $iss;
			$this->iat = $iat;
			$this->exp = $exp;
			$this->data = [ "user-id" => $userId, ];
		}
		
		/**
		 * Converts the token object into json
		 */
		public function toJson() : string
		{
			$dataJson = json_encode($this->data);
			
			return "{
			\"iss\": \"$this->iss\",
			\"iat\": \"{$this->iat->format('Y-m-d H:i:s')}\",
			\"exp\": \"{$this->exp->format('Y-m-d H:i:s')}\",
			\"data\": $dataJson
		}";
		}
	}