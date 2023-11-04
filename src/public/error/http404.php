<?php
include('/var/www/html/src/public/ui-components/error.php');

error([
    'statusCode' => 'HTTP 404',
    'imageUrl' => 'https://http.cat/404',
    'altText' => 'HTTP 404',
    'mainMessage' => 'The page you are looking for is not found 😭',
    'wikiLink' => 'https://en.wikipedia.org/wiki/List_of_HTTP_status_codes',
    'description' => 'The requested resource could not be found but may be available in the future. Subsequent requests by the
            client are permissible'
]);