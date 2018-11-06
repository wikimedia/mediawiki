<?php

if ( PHP_SAPI !== 'cli' ) {
	exit( 1 );
}

for ( $i = 1; $i < count( $argv ); $i++ ) {
	fprintf( STDOUT, "%s", getenv( $argv[$i] ) );

	if ( $i + 1 < count( $argv ) )
		fprintf( STDOUT, "\n" );
}
