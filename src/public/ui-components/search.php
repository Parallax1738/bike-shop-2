<?php

require_once('button.php');

function search($actionURL)
{
    $iconSVG = '<div class="material-symbols-outlined">search</div>';

?>
<form method="GET" action="<?php echo htmlspecialchars($actionURL); ?>" class="flex items-center w-auto gap-2">
    <input type="text" id="nav-search" name="q" placeholder="Search"
        class=" rounded p-2 focus:outline-none ring-orange-500 focus:ring border" />
    <?php
        button([
            'iconSVG' => $iconSVG,
            'type' => 'submit',
            'action' => 'search',
            'text' => '',
        ]);
        ?>
</form>
<?php
}
?>