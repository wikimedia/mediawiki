<?

function wfSpecialUserlogout()
{
	global $wgUser, $wgOut, $returnto;

	$wgUser->logout();
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->addHTML( wfMsg( "logouttext" ) . "\n<p>" );
	$wgOut->returnToMain();
}

?>
