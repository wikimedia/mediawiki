<?php

if ( PHP_SAPI !== 'cli' ) {
	exit( 1 );
}

echo str_repeat( '*', 333333 );
