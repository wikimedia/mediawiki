<?php

/** 
 * Test for *.php5 capability in webserver
 * Used by includes/templates/PHP4.php
 */
if ( version_compare( phpversion(), '5.1.0' ) >= 0 ) {
	echo 'y'.'e'.'s';
}
