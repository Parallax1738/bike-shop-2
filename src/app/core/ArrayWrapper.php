<?php
	
	namespace bikeshop\app\core;
	
	/**
	 * Wraps an array to provide more useful object-oriented functionality
	 */
	class ArrayWrapper
	{
		public function __construct(public array $arr)
		{
		}
		
		public function getArray() : array
		{
			return $this->arr;
		}
		
		public function getValueWithKey(string $key) : mixed
		{
			if ($this->keyExists($key)) {
				return $this->arr[ $key ];
			} else {
				return null;
			}
		}
		
		/**
		 * Searches through an array to see if the specified key exists in the array
		 * @param string $key Key inside array to search for
		 * @return bool Whether the element was found
		 */
		public function keyExists(string $key) : bool
		{
			return array_key_exists($key, $this->arr) && !empty($this->arr[ $key ]);
		}
	}