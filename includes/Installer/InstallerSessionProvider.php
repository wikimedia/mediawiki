<?php
/**
 * Session provider which always provides the same session ID and doesn't
 * persist the session. For use in the installer when ObjectCache doesn't
 * work anyway.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\Request\WebRequest;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionProvider;

class InstallerSessionProvider extends SessionProvider {
	/**
	 * Pretend there is a session, to avoid MWCryptRand overhead
	 * @param WebRequest $request
	 * @return SessionInfo
	 */
	public function provideSessionInfo( WebRequest $request ) {
		return new SessionInfo( 1, [
			'provider' => $this,
			'id' => str_repeat( 'x', 32 ),
		] );
	}

	/**
	 * Yes we will treat your data with great care!
	 * @return bool
	 */
	public function persistsSessionId() {
		return true;
	}

	/**
	 * Sure, you can be whoever you want, as long as you have ID 0
	 * @return bool
	 */
	public function canChangeUser() {
		return true;
	}

	public function persistSession( SessionBackend $session, WebRequest $request ) {
	}

	public function unpersistSession( WebRequest $request ) {
	}
}
