<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Redirects a user to hir personal page, used by buildPersonalUrls() in
 * SkinTemplate.php.
 */
function wfSpecialMypage() {
	global $wgUser, $wgOut;
	$t = Title::makeTitle( NS_USER, $wgUser->getName() );
	$wgOut->redirect ($t->getFullURL());
}
?>
