<nav class="bg-black p-6">
    <div class="flex items-center justify-between">
        <div class="w-1/4"></div>
        <div class="w-1/2 font-bold text-center text-white text-7xl">
            <a class="hover:text-gray-300" href="/">Bike shop</a>
        </div>
        <div class="w-1/4 flex justify-end items-center space-x-4 gap-2">
            <?php
				use bikeshop\app\database\models\DbUserModel;
				include( 'button.php' );
                
                if (isset($loggedInUser) && $loggedInUser instanceof DbUserModel)
				{
                    // We are logged in!
					button([
						'text' => 'Login',
						'targetPage' => '/auth/login',
						'isLoggedIn' => true
					]);
					
					button([
						'text' => 'Logout',
						'targetPage' => '/auth/logout',
					]);
                    
                    if ($loggedInUser->getUserRoleId() == 4)
					{
                        // User is a system administrator -> allow them to access sys-admin pages. Remember, sys-admin
                        // role has a USER_ROLE_ID = 4
						
						button([
							'text' => 'SysAdmin',
							'targetPage' => '/sys-admin'
						]);
                    }
                }
                else
				{
                    // Not logged in. Give the user an option to login or create an account
                    button([
                        'text' => 'Login',
                        'targetPage' => '/auth/login',
                        'isLoggedIn' => false
                    ]);
                    
					button([
						'text' => 'Create an account',
						'targetPage' => '/auth/create-account',
						'isLoggedIn' => false
					]);
                }
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <style>
                svg {
                    fill: #ffffff
                }
                </style>
                <path
                    d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
            </svg>
        </div>
    </div>
    <div class="mt-4 text-center text-white space-x-4">
        <a class="hover:text-gray-300" href="/bikes">Bikes</a>
        <a class="hover:text-gray-300" href="/scooters">Scooters</a>
        <a class="hover:text-gray-300" href="/accessories">Accessories</a>
        <a class="hover:text-gray-300" href="/apparel">Apparel</a>
        <a class="hover:text-gray-300" href="/components">Components</a>
    </div>
    <div>
    </div>
</nav>