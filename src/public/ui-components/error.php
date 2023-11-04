<?php
function error($params)
{
    $statusCode = $params['statusCode'] ?? 'Error';
    $imageUrl = $params['imageUrl'] ?? 'https://http.cat/404';
    $altText = $params['altText'] ?? 'Error';
    $mainMessage = $params['mainMessage'] ?? 'An unexpected error occurred';
    $wikiLink = $params['wikiLink'] ?? 'https://en.wikipedia.org/wiki/List_of_HTTP_status_codes';
    $description = $params['description'] ?? 'No description provided.';
?>
<div class="flex items-center justify-center text-center">
    <div class="max-w-lg w-full">
        <h1 class="text-4xl font-bold my-6"><?php echo $statusCode; ?></h1>
        <img class="mx-auto mb-6 rounded" src="<?php echo $imageUrl; ?>" alt="<?php echo $altText; ?>">
        <p class="text-lg mb-4"><?php echo $mainMessage; ?></p>
        <p class="mb-4">
            <a href="<?php echo $wikiLink; ?>" class="text-orange-500 underline hover:text-orange-600 ">
                Wikipedia Definition:
            </a>
            <?php echo $description; ?>
        </p>
    </div>
</div>
<?php
}
?>