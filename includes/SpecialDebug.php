<?php
/**
 * Let developpers receive the full phpinfo output
 */
 
/**
 *
 */
function wfSpecialDebug() {
	global $wgUser, $wgOut;

	if ( ! $wgUser->isDeveloper() ) {
		$wgOut->developerRequired();
		return;
	}
	phpinfo();
}
