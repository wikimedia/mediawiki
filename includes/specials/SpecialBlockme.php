<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

/**
 * @file
 * @ingroup SpecialPage
 */

function wfSpecialBlockme() {
	global $wgRequest, $wgBlockOpenProxies, $wgOut, $wgProxyKey;

	$ip = wfGetIP();

	if( !$wgBlockOpenProxies || $wgRequest->getText( 'ip' ) != md5( $ip . $wgProxyKey ) ) {
		$wgOut->addWikiMsg( 'proxyblocker-disabled' );
		return;
	}

	$blockerName = wfMsg( "proxyblocker" );
	$reason = wfMsg( "proxyblockreason" );

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

	$wgOut->addWikiMsg( "proxyblocksuccess" );
}
