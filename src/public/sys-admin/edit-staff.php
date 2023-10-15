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
		<input name="' . $htmlName . '" value="' . $value . '" type="' . $fieldType . '"/>
		</div>';
	}
	
	use bikeshop\app\models\EditUserModel;
	
	if (!isset($data) || !($data instanceof EditUserModel))
	{
		echo "Data is not set";
		die;
	}
	$user = $data->getUserModel();
	
	echo '<h1><b>Edit ' . $user->getEmailAddress() . '</b></h1>';
	echo createInputField('first-name', "First Name", $user->getFirstName());
	echo createInputField('first-name', "Last Name", $user->getLastName());
	echo createInputField('first-name', "Email Address", $user->getFirstName());
	echo createInputField('first-name', "Password", null, "password");
	