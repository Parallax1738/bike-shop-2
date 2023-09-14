<?php
	// TODO - Should SET methods be private?
	// TODO - Also validate length of strings
class DbUserModel
{
	private int $id;
	private string $firstName;
	private string $lastName;
	private string $emailAddress;
	private string $password;
	private string $address;
	private string $suburb;
	private string $state;
	private string $postcode;
	private string $country;
	private string $phone;
	
	public function __construct(
		int $id,
		string $firstName,
		string $lastName,
		string $emailAddress,
		string $password,
		string $address,
		string $suburb,
		string $state,
		string $postcode,
		string $country,
		string $phone
	)
	{
		$this->setId($id);
		$this->setFirstName($firstName);
		$this->setLastName($lastName);
		$this->setEmailAddress($emailAddress);
		$this->setPassword($password);
		$this->setAddress($address);
		$this->setSuburb($suburb);
		$this->setState($state);
		$this->setPostcode($postcode);
		$this->setCountry($country);
		$this->setPhone($phone);
	}
	
	public function print() : void
	{
		echo '<p>' . $this->getFirstName() . ' ' . $this->getLastName() . '</p>';
	}
	
	/**
	 * @return int
	 */
	public  function getId(): int
	{
		return $this->id;
	}
	
	/**
	 * @param int $id
	 */
	public  function setId(int $id):void
	{
		$this->id = $id;
	}
	
	/**
	 * @return string
	 */
	public  function getFirstName(): string
	{
		return $this->firstName;
	}
	
	/**
	 * @param string $firstName
	 */
	public  function setFirstName(string $firstName):void
	{
		$firstName = trim($firstName);
		$this->firstName = $firstName;
	}
	
	/**
	 * @return string
	 */
	public  function getLastName(): string
	{
		return $this->lastName;
	}
	
	/**
	 * @param string $lastName
	 */
	public  function setLastName(string $lastName):void
	{
		$lastName = trim($lastName);
		$this->lastName = $lastName;
	}
	
	/**
	 * @return string
	 */
	public  function getEmailAddress(): string
	{
		return $this->emailAddress;
	}
	
	/**
	 * @param string $emailAddress
	 */
	public  function setEmailAddress(string $emailAddress):void
	{
		$emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);
		$this->emailAddress = $emailAddress;
	}
	
	/**
	 * @return string
	 */
	public  function getPassword(): string
	{
		return $this->password;
	}
	
	/**
	 * @param string $password
	 */
	public  function setPassword(string $password):void
	{
		$this->password = $password;
	}
	
	/**
	 * @return string
	 */
	public  function getAddress(): string
	{
		return $this->address;
	}
	
	/**
	 * @param string $address
	 */
	public  function setAddress(string $address):void
	{
		// TODO - Better way to sanitise address?
		$address = trim($address);
		$this->address = $address;
	}
	
	/**
	 * @return string
	 */
	public  function getSuburb(): string
	{
		return $this->suburb;
	}
	
	/**
	 * @param string $suburb
	 */
	public  function setSuburb(string $suburb):void
	{
		$suburb = trim($suburb);
		$this->suburb = $suburb;
	}
	
	/**
	 * @return string
	 */
	public  function getState(): string
	{
		return $this->state;
	}
	
	/**
	 * @param string $state
	 */
	public  function setState(string $state):void
	{
		$state = trim($state);
		$this->state = $state;
	}
	
	/**
	 * @return string
	 */
	public  function getPostcode(): string
	{
		return $this->postcode;
	}
	
	/**
	 * @param string $postcode
	 */
	public  function setPostcode(string $postcode):void
	{
		$postcode = trim($postcode);
		$this->postcode = $postcode;
	}
	
	/**
	 * @return string
	 */
	public  function getCountry(): string
	{
		return $this->country;
	}
	
	/**
	 * @param string $country
	 */
	public  function setCountry(string $country):void
	{
		$country = trim($country);
		$this->country = $country;
	}
	
	/**
	 * @return string
	 */
	public  function getPhone(): string
	{
		return $this->phone;
	}
	
	/**
	 * @param string $phone
	 */
	public  function setPhone(string $phone):void
	{
		$phone = trim($phone);
		$this->phone = $phone;
	}
}