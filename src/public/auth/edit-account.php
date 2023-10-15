<?php
	/**
	 * Creates an input field, with a label and default value
	 * @param string $htmlName The name that will appear in POST
     * @param string $label Text to go aside the field
	 * @param mixed|null $value The value to put in it
	 * @param string $fieldType The type of data to be put in the field. For example, "text", "password", or "number"
     * @return void
	 */
	function createInputField(string $htmlName, string $label, mixed $value = null, string $fieldType = "text"): string
	{
		return '
		<div>
		<label>' . $label . '</label>
		<input name="' . $htmlName . '" value="' . $value . '" type="' . $fieldType . '" style="background-color: lightgrey"/>
		</div>';
	}
	
	use bikeshop\app\models\EditUserModel;
	
	if (!isset($data) || !($data instanceof EditUserModel))
	{
		echo "Data is not set";
		die;
	}
	$user = $data->getUserModel();
	
	echo '<form method="post">';
	echo '<h1><b>Edit ' . $user->getFirstName() . ' ' . $user->getLastName() . '</b></h1>';
	echo createInputField('id', "", $user->getId(), "hidden");
	echo createInputField('first-name', "First Name", $user->getFirstName());
	echo createInputField('last-name', "Last Name", $user->getLastName());
	echo createInputField('email', "Email Address", $user->getEmailAddress());
	echo createInputField('address', "Address", $user->getAddress());
	echo createInputField('suburb', "Suburb", $user->getSuburb());
	echo createInputField('state', "State", $user->getState());
	echo createInputField('postcode', "Postcode", $user->getPostcode(), "number");
	echo createInputField('country', "Country", $user->getCountry());
	echo createInputField('phone', "Phone", $user->getPhone());
	
	echo createInputField('password', "Password", null, "password");
	echo '<input type=submit value="Update User" style="color: blue; text-decoration: underline"/>';
	echo '<br>';
	echo '<a type=submit href="/auth/delete-account" style="color: blue; text-decoration: underline">Delete Account</a>';
	echo '</form>';