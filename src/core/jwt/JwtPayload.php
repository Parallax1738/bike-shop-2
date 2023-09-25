<?php
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
	private string $iat;
	
	/**
	 * Expiry: When the token expires
	 */
	private string $exp;
	
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
	public function __construct(string $iss, string $iat, string $exp, array $data)
	{
		$this->iss = $iss;
		$this->iat = $iat;
		$this->exp = $exp;
		$this->data = $data;
	}
	
	/**
	 * Converts the token object into json
	 */
	public function toJson()
	{
		$dataJson = json_encode($this->data);
		
		
		return "{
			\"iss\": \"{$this->iss}\",
			\"iat\": \"{$this->iat}\",
			\"exp\": \"{$this->exp}\",
			\"data\": [ {$dataJson} ]
		}";
	}
}