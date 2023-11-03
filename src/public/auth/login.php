<?php
require '/var/www/html/src/public/ui-components/input.php';
?>

<div class="flex pt-10 justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <?php
        // IF THE USER HAS A TOKEN, REDIRECT TO HOME SCREEN
        // TODO - Move this into another page as it breaks the MVC thing
        use bikeshop\app\core\authentication\JwtToken;
        use bikeshop\app\models\LoginSuccessModel;

        if (!empty($data) && $data instanceof LoginSuccessModel) {
            echo "
                <script>
                cookieStore.set('token', '" . $data->getToken()->encode() . "');
                location.href = '/';
                </script>
                ";
        }
        ?>

        <h1 class="text-2xl font-bold mb-4">Login</h1>
        <form action="/auth/login" method="post" class="space-y-4">
            <?php
            echo input('email', 'Email Address', null, 'email', true);
            echo input('password', 'Password', null, 'password', true);

            ?>
            <?php button(['text' => 'Login', 'type' => 'submit']); ?>
        </form>
        <hr class="my-4">
        <h1 class="text-2xl font-bold mb-4">Don't have an account?</h1>
        <div class="mb-2">
            <?php button(['text' => 'Create Account', 'targetPage' => '/auth/create-account']); ?>
        </div>
    </div>
</div>