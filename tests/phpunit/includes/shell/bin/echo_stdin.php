<?php

if ( PHP_SAPI !== 'cli' ) {
	exit( 1 );
}

$input_data = stream_get_contents( STDIN );
echo $input_data;
