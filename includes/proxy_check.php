<?php

# Command line script to check for an open proxy at a specified location

# Exit if there are not enough parameters, or if it's not command line mode

if ( ( isset( $_REQUEST ) && array_key_exists( "argv", $_REQUEST ) ) || count( $argv ) < 4 ) {
	exit();
}

# Get parameters
$ip = $argv[1];
$port = $argv[2];
$url = $argv[3];

# Open socket
$sock = fsockopen($ip, $port, $errno, $errstr, 5);
if ($errno == 0 ) {
	# Send payload
	$request = "GET $url HTTP/1.0\r\n";
	$request .= "Proxy-Connection: Keep-Alive\r\n";
	$request .= "Pragma: no-cache\r\n";
	$request .= "Host: ".$url."\r\n";
	$request .= "User-Agent: MediaWiki open proxy check\r\n";
	$request .= "\r\n";
	fputs($sock, $request);
	$response = fgets($sock, 128);
	fclose($sock);
}
?>
