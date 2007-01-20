<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 * constructor
 */
function wfSpecialUserlogout() {
	global $wgUser, $wgOut;

	if (wfRunHooks('UserLogout', array(&$wgUser))) {

		$wgUser->logout();

		wfRunHooks('UserLogoutComplete', array(&$wgUser));

		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addHTML( wfMsgExt( 'logouttext', array( 'parse' ) ) );
		$wgOut->returnToMain();

	}
}

?>
