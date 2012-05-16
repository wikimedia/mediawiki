<?php

/**
 *
 *
 */

if ( isset( $wgUseBuiltinHTMLPurifier ) && $wgUseBuiltinHTMLPurifier === true ) {
	//Allow users to use a built-in HTMLPurifier library, for systems that support it
	require_once 'HTMLPurifier.auto.php';
} else {
	require_once dirname( __FILE__ ) . '/HTMLPurifier/HTMLPurifier.standalone.php';
}


