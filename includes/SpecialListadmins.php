<?php
/**
 * This old a redirect to Special:Listusers that now implement a filter
 * by user groups. The listadmins special page is now deprecated but kept
 * for backward compatibility.
 * 
 * @package MediaWiki
 * @subpackage SpecialPage
 * @deprecated
 */

/**
 * Just redirect to Special:Listusers.
 * Kept for backward compatibility.
 */
function wfSpecialListadmins() {
	global $wgOut;
	$t = Title::makeTitle( NS_SPECIAL, "Listusers" );
	$wgOut->redirect ($t->getFullURL());
}


?>
