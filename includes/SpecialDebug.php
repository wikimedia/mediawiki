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

	if ( ! $wgUser->isAllowed('siteadmin') ) {
		$wgOut->developerRequired();
		return;
	}
	phpinfo();
}
