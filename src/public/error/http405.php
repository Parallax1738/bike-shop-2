<?php
include('/var/www/html/src/public/ui-components/error.php');

error([
    'statusCode' => 'HTTP 405',
    'imageUrl' => 'https://http.cat/405',
    'altText' => 'HTTP 405',
    'mainMessage' => 'You tried to read/write data to the wrong place in the server :(',
    'wikiLink' => 'https://en.wikipedia.org/wiki/List_of_HTTP_status_codes',
    'description' => 'A request method is not supported for the requested resource; for example, a GET request on a form that requires data to be presented via POST, or a PUT request on a read-only resource.'
]);