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

	$wgUser->logout();
	$wgOut->setRobotpolicy( 'noindex,nofollow' );
	$wgOut->addHTML( wfMsgExt( 'logouttext', array( 'parse' ) ) );
	$wgOut->returnToMain();
}


