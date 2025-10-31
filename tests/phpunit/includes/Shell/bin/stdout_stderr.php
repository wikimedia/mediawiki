<?php

if ( PHP_SAPI !== 'cli' ) {
	exit( 1 );
}

file_put_contents( "php://stdout", $argv[1] );
if ( isset( $argv[2] ) ) {
	file_put_contents( "php://stderr", $argv[2] );
}
