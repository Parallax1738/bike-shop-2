<?php
	use bikeshop\app\database\entity\ProductEntity;
	use bikeshop\app\models\CartModel;
	use bikeshop\app\models\CartProductEntity;
	use Money\Currency;
	use Money\Money;
	
	if (!isset($data) || !($data instanceof CartModel))
	{
		echo 'No model was provided';
		die;
	}
	
	if (count($data->getProducts()) == 0)
	{
		echo '<p>No Products in your cart.</p>';
	}
	else {
        $total = 0;
        echo '
            <h1>Cart</h1>
            <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price Per Unit</th>
                    <th>Sub Total</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($data->getProducts() as $p)
        {
            // Info - Uses javascript to load the quantities
            if ($p instanceof ProductEntity)
            {
                echo '<tr>';
                echo '<td>' . $p->getName() . '</td>';
                echo '<td>$' . $p->getPrice() . '</td>';
                echo '<td><p class="sub-total" data-id="'.$p->getId().'">$0.00</p></td>';
				echo '<td><input class="quantity" min=0 data-id="'.$p->getId().'" data-price="'.$p->getPrice().'" type="number" value="-1" step="1"/></td>';
                echo '<td><a href="/products/details?product=' . $p->getId() . '" style="text-decoration: underline; color: blue;">View Details</a></td>';
                echo '<tr>';
            }
        }
        echo '
            </tbody>
            </table>
            <br><hr><br><p id="total">Total: $' . $total . '</p>
            <a style="color: blue; text-decoration: underline">Purchase</a>';
    }
	?>
<script>
	const QUANTITY_INPUTS = document.querySelectorAll('.quantity')
    const SUB_TOTAL_INPUTS = document.querySelectorAll('.sub-total');
    const TOTAL_TEXT = document.querySelector('#total');
    
    // Make all quantity inputs run the changeQuantityInCart() and update text event when it is changed
    for (let i = 0; i < QUANTITY_INPUTS.length; i++)
    {
        QUANTITY_INPUTS[i].addEventListener("input", (event) => {
            let quantity = event.target.value;
            let productId = event.target.dataset.id;
            changeQuantityInCart(Number(productId), Number(quantity)).then(() => {
                // Once the quantity changes in the cookie, reload teh prices
                initQuantities();
            });
        })
    }
    
    // Then actually init the quantities initially
    initQuantities();
    
    function initQuantities() {
        getCart().then((cart) => {
            let total = 0;
            
            for (let i = 0; i < QUANTITY_INPUTS.length; i++) {
                let foundProductId = findProductInCart(cart, QUANTITY_INPUTS[i].dataset.id);
                if (foundProductId === -1) continue;
                
                let price = QUANTITY_INPUTS[i].dataset.price ?? 0;
                let quantity = cart[i]['q'];
                let subtotalPrice = price * quantity;
                
                // Set Quantity
                QUANTITY_INPUTS[i].value = quantity;
                SUB_TOTAL_INPUTS[i].innerHTML = "$" + subtotalPrice.toFixed(2);
                
                total += price * quantity;
            }
            TOTAL_TEXT.innerHTML = "Total: $" + total.toFixed(2);
        });
    }
</script>
