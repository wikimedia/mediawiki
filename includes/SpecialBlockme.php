<?php
/**
 *
 */

/**
 *
 */
function wfSpecialBlockme()
{
	global $wgIP, $wgBlockOpenProxies, $wgOut, $wgProxyKey;

	if ( !$wgBlockOpenProxies || $_REQUEST['ip'] != md5( $wgIP . $wgProxyKey ) ) {
		$wgOut->addWikiText( wfMsg( "disabled" ) );
		return;
	}       

	$blockerName = wfMsg( "proxyblocker" );
	$reason = wfMsg( "proxyblockreason" );
	$success = wfMsg( "proxyblocksuccess" );

	$u = User::newFromName( $blockerName );
	$id = $u->idForName();
	if ( !$id ) {
		$u = User::newFromName( $blockerName );
		$u->addToDatabase();
		$u->setPassword( bin2hex( mt_rand(0, 0x7fffffff ) ) );
		$u->saveSettings();
		$id = $u->getID();
	}

	$block = new Block( $wgIP, 0, $id, $reason, wfTimestampNow() );
	$block->insert();

	$wgOut->addWikiText( $success );
}
?>
