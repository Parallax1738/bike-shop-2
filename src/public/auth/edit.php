<?php

require '/var/www/html/src/public/ui-components/input.php';

use bikeshop\app\models\EditUserModel;

if (!isset($data) || !($data instanceof EditUserModel)) {
    echo "Data is not set";
    die;
}

$user = $data->getUserModel();

$idInput = input('id', "", $user->getId(), "hidden");
$firstNameInput = input('first-name', "First Name", $user->getFirstName());
$lastNameInput = input('last-name', "Last Name", $user->getLastName());
$emailInput = input('email', "Email Address", $user->getEmailAddress());
$addressInput = input('address', "Address", $user->getAddress());
$suburbInput = input('suburb', "Suburb", $user->getSuburb());
$stateInput = input('state', "State", $user->getState());
$postcodeInput = input('postcode', "Postcode", $user->getPostcode(), "number");
$countryInput = input('country', "Country", $user->getCountry());
$phoneInput = input('phone', "Phone", $user->getPhone());
$passwordInput = input('password', "Password", null, "password");

echo <<<HTML
<div class="flex pt-10 justify-center">
    <div class="bg-white p-10 rounded shadow-lg w-full max-w-2xl space-y-8">
        <h1 class="text-2xl font-bold mb-2">Edit account</h1>
        <form method="post" class="space-y-2">
            $idInput

            <div class="flex flex-wrap -mx-2 mb-2 py-2">
                <div class="w-full md:w-1/2 py-2">$firstNameInput</div>
                <div class="w-full md:w-1/2 py-2">$lastNameInput</div>
                <div class="w-full py-2">$emailInput</div>
                <div class="w-full py-2">$addressInput</div>
                <div class="w-full md:w-1/3 py-2">$suburbInput</div>
                <div class="w-full md:w-1/3 py-2">$stateInput</div>
                <div class="w-full md:w-1/3 py-2">$postcodeInput</div>
                <div class="w-full md:w-1/2 py-2">$countryInput</div>
                <div class="w-full md:w-1/2 py-2">$phoneInput</div>
                <div class="w-full">$passwordInput</div>
            </div>

            <div class="mt-8">
                <input type="submit" value="Update User" class="mb-4 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transform transition-all duration-100 hover:shadow-lg hover:scale-105">
			</div>
			
			</form>
			<form method="post" action="/auth/delete">
			<input
				type="submit"
				class="block text-center text-red-600 hover:text-white border-solid border-2 border-red-600 hover:bg-red-600 transform transition-all duration-100 hover:shadow-lg hover:scale-105 py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
				value="Delete Account" />
			</form>
    </div>
</div>
HTML;