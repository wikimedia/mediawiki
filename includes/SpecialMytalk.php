<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** @todo document */
function wfSpecialMytalk() {
	global $wgUser, $wgOut;
	$t = Title::makeTitle( NS_USER_TALK, $wgUser->getName() );
	$wgOut->redirect ($t->getFullURL());
}
?>