<?php
    namespace public;
	require "../../vendor/autoload.php";
	use bikeshop\app\core\ApplicationState;
	use bikeshop\app\database\entity\UserEntity;
	use bikeshop\public\Bootstrapper;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bike shop</title>
    <link href="../../output.css" rel="stylesheet">
    <link href="../../table.css" rel="stylesheet">
</head>

<script>
    // TODO - Find way to make separate JS files without everything breaking itself

    // Adds a product id to the cart. It is stored in an array for simplicity
    async function addToCart(productId) {
        let cart = await getCart();

        cart.push({
            'p-id': productId,
            'q': 1
        });

        await cookieStore.set('cart', createCartCookie(cart))
    }

    async function getCart() {
        let cartCookie = (await cookieStore.get('cart'))
        let cart;
        if (cartCookie) {
            // Convert from json into js object. Create new object if cookie doesn't exist
            try {
                let cartCookieValue = atob(cartCookie['value'])
                cart = JSON.parse(cartCookieValue) ?? [ ]
            } catch (e) {
                // If something goes wrong, just create a new cart
                cart = [ ]
            }
        } else {
            cart = [ ];
        }
        return cart;
    }

    function findProductInCart(cart, productId) {
        for (let i = 0; i < cart.length; i++) {
            if (cart[i]['p-id'] === Number(productId)) {
                return i;
            }
        }
        return -1;
    }

    function createCartCookie(cartStr) {
        return btoa(JSON.stringify(cartStr));
    }

    async function changeQuantityInCart(productId, quantity) {
        let cart = await getCart();

        let reloadPage = false;

        // Add item to products list
        for (let i = 0; i < cart.length; i++)
        {
            if (cart[i]['p-id'] === productId) {
                if (quantity === 0) {
                    // Delete the thing from the cookies and reload the page because I'm lazy
                    cart.splice(i, 1);
                    window.location.href = "/cart"
                    reloadPage = true;
                } else {
                    cart[i]['q'] = quantity;
                    console.log(cart[i]['q']);
                    break;
                }
            }
        }

        // Set new cookie
        await cookieStore.set('cart', createCartCookie(cart))

        if (reloadPage) window.location.href = '/cart';
    }
</script>

<body class="flex flex-col min-h-screen">
    <div class="flex-grow">
        <?php
		$bootstrapper = new Bootstrapper();
		
		// Get Application State Information
		$loggedInUser = $bootstrapper->InitAuth();
		$state = new ApplicationState($loggedInUser);
        
        // Initialise bootstrap before loading navbar for application state
        include_once './ui-components/navbar.php';
    ?>
        <main>
            <?php
            // Begin Program
            $bootstrapper->Start($state);
		?>
        </main>
    </div>
    <?php
        include_once './ui-components/footer.php';
    ?>
</body>
</html>