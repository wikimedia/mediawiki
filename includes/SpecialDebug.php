<?php
/**
 * Let developpers receive the full phpinfo output
 * @package MediaWiki
 * @subpackage SpecialPage
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
