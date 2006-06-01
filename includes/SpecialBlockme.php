<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialBlockme()
{
	global $wgBlockOpenProxies, $wgOut, $wgProxyKey;

	$ip = ProxyTools::getIP();

	if ( !$wgBlockOpenProxies || $_REQUEST['ip'] != md5( $ip . $wgProxyKey ) ) {
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

	$block = new Block( $ip, 0, $id, $reason, wfTimestampNow() );
	$block->insert();

	$wgOut->addWikiText( $success );
}
?>
