<?php
/**
 * This file contains ancient db-related functions that have been deprecated. Do
 * not use them. Please find the appropriate replacements.
 *
 * @file
 */

/**
 * @deprecated Use DatabaseBase::sourceFile(). Will probably be removed in 1.19
 */
function dbsource( $fname, $db = false ) {
	wfDeprecated( __METHOD__ );
	if ( !$db ) {
		$db = wfGetDB( DB_MASTER );
	}
	$error = $db->sourceFile( $fname );
	if ( $error !== true ) {
		print $error;
		exit( 1 );
	}
}

/**
 * @deprecated Use DatabaseBase::patchPath(). Will probably be removed in 1.18
 */
function archive( $name ) {
	wfDeprecated( __METHOD__ );
	$dbr = wfGetDB( DB_SLAVE );
	return $dbr->patchPath( $name );
}
