<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * constructor
 */
function wfSpecialUserlogout() {
	global $wgUser, $wgOut, $returnto;

	if (wfRunHooks('UserLogout', array(&$wgUser))) {
		
		$wgUser->logout();

		wfRunHooks('UserLogoutComplete', array(&$wgUser));
		
		$wgOut->mCookies = array();
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addHTML( wfMsg( 'logouttext' ) );
		$wgOut->returnToMain();
		
	}
}

?>
