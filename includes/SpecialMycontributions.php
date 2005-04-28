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
   
function wfSpecialMycontributions() {
	global $wgUser, $wgOut;
	$t = Title::makeTitle( NS_SPECIAL, 'Contributions' );
	$url = $t->getFullURL( 'target=' . urlencode( $wgUser->getName() ) );
	$wgOut->redirect( $url );
}
?>
