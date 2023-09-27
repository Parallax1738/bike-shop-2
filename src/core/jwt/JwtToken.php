<?php
	require_once 'JwtToken.php';
	
	/**
	 * A token that stores data about a user when they successfully authenticate. With this token, a has more functionality
	 * available to them inside the website
	 */
	class JwtToken
	{
		/**
		 * The key used to sign the jwt token
		 */
		private const SECRET_KEY = "password";
		
		/**
		 * the type and algorithm used to sign the key. A example implementation could be:
		 * ```[
		 * 'typ' => 'JWT',
		 * 'alg' => 'HS256'
		 * ];```
		 * @var array The data inside the header
		 */
		private array $header;
		
		private JwtPayload $payload;
		
		public function __construct(array $header, JwtPayload $payload)
		{
			$this->header = $header;
			$this->payload = $payload;
		}
		
		public function getPayload(): JwtPayload
		{
			return $this->payload;
		}
		
		/**
		 * Converts the object into a hashed token containing all the data that you gave it in the constructor
		 * @return string The token itself
		 */
		public function encode(): string
		{
			$jsonHeader = json_encode($this->header);
			$jsonPayload = $this->payload->toJson();
			
			// Encode the header and payload
			$encodedHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($jsonHeader));
			$encodedPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($jsonPayload));
			
			// Create the signature
			$signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, self::SECRET_KEY, true);
			$encodedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
			
			return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
		}
		
		public static function decode(string $token): JwtToken|null
		{
			$parts = explode('.', $token);
			if (count($parts) == 3) {
				$header = $parts[0];
				$payload = $parts[1];
				$receivedSignature = $parts[2];
				
				// Verify signature by re-hashing the header and payload together using SECRET_KEY which only this
				// application will know about; only this application will be able to generate a valid
				// signature in the first place
				$expectedSignature = hash_hmac('sha256', $header . '.' . $payload, self::SECRET_KEY, true);
				$expectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));
				
				if ($receivedSignature != $expectedSignature)
				{
					return null;
				}
				
				$payload = base64_decode($payload);
				$header = base64_decode($header);
			}
			
			return new JwtToken([], new JwtPayload("", new DateTime(), new DateTime(), 0));
		}
	}