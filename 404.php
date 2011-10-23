<?php

header( 'HTTP/1.1 404 Not Found' );
header( 'Content-Type: text/html;charset=utf-8' );

# $_SERVER['REQUEST_URI'] has two different definitions depending on PHP version
if ( preg_match( '!^([a-z]*://)([a-z.]*)(/.*)$!', $_SERVER['REQUEST_URI'], $matches ) ) {
	$prot = $matches[1];
	$serv = $matches[2];
	$loc = $matches[3];
} else {
	$prot = "http://";
	$serv = strlen( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
	$loc = $_SERVER["REQUEST_URI"];
}
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

echo $standard_404;
