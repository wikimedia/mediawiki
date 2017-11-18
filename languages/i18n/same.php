<?php

$en = (array)json_decode( file_get_contents( 'en.json' ) );
$en_gb = (array)json_decode( file_get_contents( 'en-ca.json' ) );

$messagesIdentical = [];

foreach ( $en_gb as $k => $v ) {

	if ( isset( $en[$k] ) && $en[$k] === $v ) {
		$messagesIdentical[] = $k;
	}
}

echo count( $messagesIdentical ) . " identical messages.";
echo "\n";
echo implode( "\n", $messagesIdentical );
echo "\n";
