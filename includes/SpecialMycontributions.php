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
	$wgOut->redirect ( $t->getFullURL().'&target='.$wgUser->getName() );
}
?>