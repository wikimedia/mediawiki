<?php

function wfSpecialUserlogout()
{
	global $wgUser, $wgOut, $returnto;

	$wgUser->logout();
	$wgOut->mCookies = array();
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->addHTML( wfMsg( "logouttext" ) . "\n<p>" );
	$wgOut->returnToMain();
}

?>
