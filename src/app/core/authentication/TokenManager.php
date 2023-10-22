<?php
	namespace bikeshop\app\core\authentication;
	
	use bikeshop\app\database\DatabaseConnector;
	use bikeshop\app\database\entity\UserEntity;
	use Exception;
	
	class TokenManager
	{
		private DatabaseConnector $db;
		
		public function __construct()
		{
			$this->db = new DatabaseConnector("user", "password", "BIKE_SHOP");
		}
		
		/**
		 * Checks a token to see whether or not it is valid
		 * @param string $token The token that was generated by /auth/login
		 * @return UserEntity
		 * @throws Exception If the token was invalid
		 */
		public function verifyToken(string $token) : UserEntity | null
		{
			// Get token object from string
			$parts = explode('.', $token);
			if (count($parts) != 3)
			{
				throw new Exception("Invalid token provided. Please login again");
			}
			
			$header = $parts[ 0 ];
			$payload = $parts[ 1 ];
			$receivedSignature = $parts[ 2 ];
			
			// Verify signature by re-hashing the header and payload together using SECRET_KEY which only this
			// application will know about; only this application will be able to generate a valid
			// signature in the first place
			$expectedSignature = hash_hmac('sha256', $header . '.' . $payload, JwtToken::SECRET_KEY, true);
			$expectedSignature = str_replace([ '+', '/', '=' ], [ '-', '_', '' ], base64_encode($expectedSignature));
			
			if ($receivedSignature != $expectedSignature)
			{
				throw new Exception("Invalid token provided. Please login again");
			}
			
			$payload = base64_decode($payload);
			$header = base64_decode($header);
			
			// Check expiry date. If it is passed the expiry date, reroute to /auth/login and clear the token cookie
			$payload = json_decode($payload, true);

			if (!array_key_exists('exp', $payload) || empty($payload[ 'exp' ]))
			{
				throw new Exception("Token has expired. Please login again");
			}
			
			if (!array_key_exists('data', $payload) || empty($payload[ 'data' ]) &&
				!array_key_exists('user-id', $payload['data']) || empty($payload['data'][ 'user-id' ]))
			{
				throw new Exception("Invalid token provided. Please login again");
			}
			
			return $this->db->findUserWithId($payload['data']['user-id']);
		}
		
		public function createDefaultSysAdmin()
		{
			
		}
	}