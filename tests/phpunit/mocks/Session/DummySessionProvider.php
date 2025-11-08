<?php

use MediaWiki\Request\WebRequest;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionProvider;
use MediaWiki\Session\UserInfo;

/**
 * Dummy session provider
 *
 * An implementation of a session provider that doesn't actually do anything.
 */
class DummySessionProvider extends SessionProvider {

	public const ID = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

	/** @inheritDoc */
	public function provideSessionInfo( WebRequest $request ) {
		return new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this,
			'id' => self::ID,
			'persisted' => true,
			'userInfo' => UserInfo::newAnonymous(),
		] );
	}

	/** @inheritDoc */
	public function newSessionInfo( $id = null ) {
		return new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => $id,
			'idIsSafe' => true,
			'provider' => $this,
			'persisted' => false,
			'userInfo' => UserInfo::newAnonymous(),
		] );
	}

	/** @inheritDoc */
	public function persistsSessionId() {
		return true;
	}

	/** @inheritDoc */
	public function canChangeUser() {
		return $this->persistsSessionId();
	}

	/** @inheritDoc */
	public function persistSession( SessionBackend $session, WebRequest $request ) {
	}

	public function unpersistSession( WebRequest $request ) {
	}

	/** @inheritDoc */
	public function preventSessionsForUser( $user ) {
	}

	/** @inheritDoc */
	public function suggestLoginUsername( WebRequest $request ) {
		return $request->getCookie( 'UserName' );
	}

}
