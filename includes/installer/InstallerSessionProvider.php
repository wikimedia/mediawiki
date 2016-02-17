<?php
/**
 * Session provider which always provides the same session ID and doesn't
 * persist the session. For use in the installer when ObjectCache doesn't
 * work anyway.
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
 *
 * @file
 * @ingroup Deployment
 */

use MediaWiki\Session\SessionProvider;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionInfo;

class InstallerSessionProvider extends SessionProvider {
	/**
	 * Pretend there is a session, to avoid MWCryptRand overhead
	 */
	public function provideSessionInfo( WebRequest $request ) {
		return new SessionInfo( 1, [
			'provider' => $this,
			'id' => str_repeat( 'x', 32 ),
		] );
	}

	/**
	 * Yes we will treat your data with great care!
	 */
	public function persistsSessionId() {
		return true;
	}

	/**
	 * Sure, you can be whoever you want, as long as you have ID 0
	 */
	public function canChangeUser() {
		return true;
	}

	public function persistSession( SessionBackend $session, WebRequest $request ) {
	}

	public function unpersistSession( WebRequest $request ) {
	}
}
