<?php

# Valid web server entry point
define( 'THUMB_HANDLER', true );

if ( $_SERVER['REQUEST_URI'] === $_SERVER['SCRIPT_NAME'] ) {
	# Directly requesting this script is not a use case.
	# Instead of giving a thumbnail error, give a generic 404.
	wfDisplay404Error(); // go away, nothing to see here
} else {
	# Execute thumb.php, having set THUMB_HANDLER so that
	# it knows to extract params from a thumbnail file URL.
	require( dirname( __FILE__ ) . '/thumb.php' );
}

/**
 * Print out a generic 404 error message
 *
 * @return void
 */
function wfDisplay404Error() {
	header( 'HTTP/1.1 404 Not Found' );
	header( 'Content-Type: text/html;charset=utf-8' );

	$prot = isset( $_SERVER['HTTPS'] ) ? "https://" : "http://";
	$serv = strlen( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
	$loc = $_SERVER["REQUEST_URI"];

	$encUrl = htmlspecialchars( $prot . $serv . $loc );

	// Looks like a typical apache2 error
	$standard_404 = <<<ENDTEXT
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL $encUrl was not found on this server.</p>
</body></html>
ENDTEXT;

	print $standard_404;
}
