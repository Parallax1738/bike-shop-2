<?php
include('/var/www/html/src/public/ui-components/error.php');

error([
    'statusCode' => 'HTTP 403',
    'imageUrl' => 'https://http.cat/403',
    'altText' => 'HTTP 403',
    'mainMessage' => 'Similar to HTTP401, you may simply not have the permissions necessary to view this resource. Please try logging in again',
    'wikiLink' => 'https://en.wikipedia.org/wiki/List_of_HTTP_status_codes',
    'description' => 'The request contained valid data and was understood by the server, but the server is refusing action. This may be due to the user not having the necessary permissions for a resource or needing an account of some sort, or attempting a prohibited action (e.g. creating a duplicate record where only one is allowed). This code is also typically used if the request provided authentication by answering the WWW-Authenticate header field challenge, but the server did not accept that authentication. The request should not be repeated.'
]);