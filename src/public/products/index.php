<?php
	use bikeshop\app\core\ArrayWrapper;
	use bikeshop\app\database\models\DbProduct;
	use bikeshop\app\database\models\DbProductFilter;
	use bikeshop\app\models\ProductsModel;
	use bikeshop\public\products\ProductsPageHelper;
	
	if (!isset($data) || !($data instanceof ProductsModel))
	{
		echo '<p>Well the data parameter isn\'t set for some reason. Good luck!</p>';
		die;
	}
	$get = new ArrayWrapper($_GET);
    $productsHelper = new ProductsPageHelper($data, $get);

    echo "<div>";
    
    // -- HEADER --
	$productsHelper->displayHeader();
    
    // -- SEARCH --
    $productsHelper->displaySearchBar();
    
    echo "</div><br><hr><br><div>";
    
    // -- PRODUCT LIST --
    $productsHelper->displayProductsList();
	
	echo "</div>";
 
	// -- PAGE CONTROL --
	$productsHelper->displayPreviousButton();
	$productsHelper->displayPageCount();
    $productsHelper->displayNextButton();
?>
<script>
    function onCheckChecked(event) {
        const val = event.target.checked;
        if (val) {
        
        }
    }
</script>
