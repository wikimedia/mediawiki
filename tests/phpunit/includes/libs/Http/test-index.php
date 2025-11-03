<?php
// Entry point used by PHP's mock built-in webserver for MultiHttpClientTest.
http_response_code( 200 );
// phpcs:ignore MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals
echo "Response for request " . $_GET['request'] . "\n";
