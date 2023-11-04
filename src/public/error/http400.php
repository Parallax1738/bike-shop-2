<?php
include('/var/www/html/src/public/ui-components/error.php');

error([
    'statusCode' => 'HTTP 400',
    'imageUrl' => 'https://http.cat/400',
    'altText' => 'HTTP 400',
    'mainMessage' => 'Either passed in irrelevant/faulty data (such as text instead of numbers), or a server error',
    'wikiLink' => 'https://en.wikipedia.org/wiki/List_of_HTTP_status_codes',
    'description' => 'The server cannot or will not process the request due to an apparent client error (e.g., malformed request syntax, size too large, invalid request message framing, or deceptive request routing).'
]);