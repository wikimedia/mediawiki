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
	
	// Hook.
	$injected_html = '';
	wfRunHooks( 'UserLogoutComplete', array(&$wgUser, &$injected_html) );
	
	$wgOut->addHTML( wfMsgExt( 'logouttext', array( 'parse' ) ) . $injected_html );
	$wgOut->returnToMain();
}