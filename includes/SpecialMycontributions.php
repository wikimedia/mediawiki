<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** @todo document */
function wfSpecialMycontributions() {
	global $wgUser, $wgOut;
	$t = Title::makeTitle( NS_SPECIAL, 'Contributions' );
	$url = $t->getFullURL( 'target=' . urlencode( $wgUser->getName() ) );
	$wgOut->redirect( $url );
}
?>