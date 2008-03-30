<?php
require_once( "commandLine.inc" );
if ( isset( $args[0] ) ) {
	wfWaitForSlaves($args[0]);
} else {
	wfWaitForSlaves(10);
}


