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

	if (wfRunHooks('UserLogout', $wgUser)) {
		
		$wgUser->logout();
	
		$wgOut->mCookies = array();
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addHTML( wfMsg( 'logouttext' ) );
		$wgOut->returnToMain();
		
		wfRunHooks('UserLogoutComplete', $wgUser);
	}
}

?>
