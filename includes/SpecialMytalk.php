<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Redirects a user to hir talk page, used by buildPersonalUrls() in
 * SkinTemplate.php.
 */
function wfSpecialMytalk() {
	global $wgUser, $wgOut;
	$t = Title::makeTitle( NS_USER_TALK, $wgUser->getName() );
	$wgOut->redirect ($t->getFullURL());
}
?>
