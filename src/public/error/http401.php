<?php 
include('/var/www/html/src/public/ui-components/error.php');

error([
    'statusCode' => 'HTTP 401',
    'imageUrl' => 'https://http.cat/401',
    'altText' => 'HTTP 401',
    'mainMessage' => 'You are unauthorised to view this page. If logging in doesn\'t help, you may not have enough user privileges to access this resource',
    'wikiLink' => 'https://en.wikipedia.org/wiki/List_of_HTTP_status_codes',
    'description' => 'Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided. The response must include a WWW-Authenticate header field containing a challenge applicable to the requested resource. See Basic access authentication and Digest access authentication. 401 semantically means "unauthorised", the user does not have valid authentication credentials for the target resource. Some sites incorrectly issue HTTP 401 when an IP address is banned from the website (usually the website domain) and that specific address is refused permission to access a website.[citation needed]'
]);