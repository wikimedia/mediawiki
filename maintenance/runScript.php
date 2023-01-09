<?php

if ( stream_isatty( STDOUT ) ) {
	echo "\n";
	echo "*******************************************************************************\n";
	echo "NOTE: The maintenance/runScript.php entry point has been replaced by run.php!\n";
	echo "*******************************************************************************\n";
	echo "\n";
}

require __DIR__ . "/run.php";
