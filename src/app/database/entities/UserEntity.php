<?php
	// TODO - Should SET methods be private?
	// TODO - Also validate length of strings
	
	namespace bikeshop\app\database\entities;
	/**
	 * A class representation of a user record inside the database
	 */
	class UserEntity
	{
		private int $id;
		private int $userRoleId;
		private string $emailAddress;
		private string | null $firstName;
		private string | null $lastName;
		private string | null $password;
		private string | null $address;
		private string | null $suburb;
		private string | null $state;
		private string | null $postcode;
		private string | null $country;
		private string | null $phone;
		
		public function __construct(int $id, int $userRoleId, string $emailAddress, string | null $firstName, string | null $lastName, string | null $password, string | null $address, string | null $suburb, string | null $state, string | null $postcode, string | null $country, string | null $phone)
		{
			$this->setId($id);
			$this->setUserRoleId($userRoleId);
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
		public function getId() : int
		{
			return $this->id;
		}
		/**
		 * @param int $id
		 */
		public function setId(int $id) : void
		{
			$this->id = $id;
		}
		
		/**
		 * @return string
		 */
		public function getFirstName() : string | null
		{
			return $this->firstName;
		}
		
		/**
		 * @param string $firstName
		 */
		public function setFirstName(string | null $firstName) : void
		{
			$firstName = trim($firstName ?? "");
			$this->firstName = $firstName;
		}
		
		/**
		 * @return string
		 */
		public function getLastName() : string | null
		{
			return $this->lastName;
		}
		
		/**
		 * @param string $lastName
		 */
		public function setLastName(string | null $lastName) : void
		{
			$lastName = trim($lastName ?? "");
			$this->lastName = $lastName;
		}
		
		/**
		 * @return string
		 */
		public function getEmailAddress() : string
		{
			return $this->emailAddress;
		}
		
		/**
		 * @param string $emailAddress
		 */
		public function setEmailAddress(string $emailAddress) : void
		{
			$emailAddress = filter_var($emailAddress, FILTER_SANITIZE_EMAIL);
			$this->emailAddress = $emailAddress;
		}
		
		/**
		 * @return string
		 */
		public function getPassword() : string | null
		{
			return $this->password;
		}
		
		/**
		 * @param string $password
		 */
		public function setPassword(string | null $password) : void
		{
			$this->password = $password;
		}
		
		/**
		 * @return string
		 */
		public function getAddress() : string | null
		{
			return $this->address;
		}
		
		/**
		 * @param string $address
		 */
		public function setAddress(string | null $address) : void
		{
			// TODO - Better way to sanitise address?
			$address = trim($address ?? "");
			$this->address = $address;
		}
		
		/**
		 * @return string
		 */
		public function getSuburb() : string | null
		{
			return $this->suburb;
		}
		
		/**
		 * @param string $suburb
		 */
		public function setSuburb(string | null $suburb) : void
		{
			$suburb = trim($suburb ?? "");
			$this->suburb = $suburb;
		}
		
		/**
		 * @return string
		 */
		public function getState() : string | null
		{
			return $this->state;
		}
		
		/**
		 * @param string $state
		 */
		public function setState(string | null $state) : void
		{
			$state = trim($state ?? "");
			$this->state = $state;
		}
		
		/**
		 * @return string
		 */
		public function getPostcode() : string | null
		{
			return $this->postcode;
		}
		
		/**
		 * @param string $postcode
		 */
		public function setPostcode(string | null $postcode) : void
		{
			$postcode = trim($postcode ?? "");
			$this->postcode = $postcode;
		}
		
		/**
		 * @return string
		 */
		public function getCountry() : string | null
		{
			return $this->country;
		}
		
		/**
		 * @param string $country
		 */
		public function setCountry(string | null $country) : void
		{
			$country = trim($country ?? "");
			$this->country = $country;
		}
		
		/**
		 * @return string
		 */
		public function getPhone() : string | null
		{
			return $this->phone;
		}
		
		/**
		 * @param string $phone
		 */
		public function setPhone(string | null $phone) : void
		{
			$phone = trim($phone ?? "");
			$this->phone = $phone;
		}
		
		public function getUserRoleId(): int
		{
			return $this->userRoleId;
		}
		
		private function setUserRoleId(int $userRoleId)
		{
			$this->userRoleId = $userRoleId;
		}
	}