<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 */
function wfSpecialBlockme() {
	global $wgRequest, $wgBlockOpenProxies, $wgOut, $wgProxyKey;

	$ip = wfGetIP();
	
	if( !$wgBlockOpenProxies || $wgRequest->getText( 'ip' ) != md5( $ip . $wgProxyKey ) ) {
		$wgOut->addWikiText( wfMsg( 'disabled' ) );
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

	$block = new Block( $ip, 0, $id, $reason, wfTimestampNow() );
	$block->insert();

	$wgOut->addWikiText( $success );
}
?>
