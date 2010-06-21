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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * constructor
 */
function wfSpecialUserlogout() {
	global $wgUser, $wgOut;

	/**
	 * Some satellite ISPs use broken precaching schemes that log people out straight after
	 * they're logged in (bug 17790). Luckily, there's a way to detect such requests.
	 */
	if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '&amp;' ) !== false ) {
		wfDebug( "Special:Userlogout request {$_SERVER['REQUEST_URI']} looks suspicious, denying.\n" );
		wfHttpError( 400, wfMsg( 'loginerror' ), wfMsg( 'suspicious-userlogout' ) );
		return;
	}
	
	$oldName = $wgUser->getName();
	$wgUser->logout();
	$wgOut->setRobotPolicy( 'noindex,nofollow' );

	// Hook.
	$injected_html = '';
	wfRunHooks( 'UserLogoutComplete', array(&$wgUser, &$injected_html, $oldName) );

	$wgOut->addHTML( wfMsgExt( 'logouttext', array( 'parse' ) ) . $injected_html );
	$wgOut->returnToMain();
}
