<?php

use MediaWiki\Session\SessionProvider;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\UserInfo;

/**
 * Dummy session provider
 *
 * An implementation of a session provider that doesn't actually do anything.
 */
class DummySessionProvider extends SessionProvider {

	const ID = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

	public function provideSessionInfo( WebRequest $request ) {
		return new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this,
			'id' => self::ID,
			'persisted' => true,
			'userInfo' => UserInfo::newAnonymous(),
		] );
	}

	public function newSessionInfo( $id = null ) {
		return new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'id' => $id,
			'idIsSafe' => true,
			'provider' => $this,
			'persisted' => false,
			'userInfo' => UserInfo::newAnonymous(),
		] );
	}

	public function persistsSessionId() {
		return true;
	}

	public function canChangeUser() {
		return $this->persistsSessionId();
	}

	public function persistSession( SessionBackend $session, WebRequest $request ) {
	}

	public function unpersistSession( WebRequest $request ) {
	}

	public function immutableSessionCouldExistForUser( $user ) {
		return false;
	}

	public function preventImmutableSessionsForUser( $user ) {
	}

	public function suggestLoginUsername( WebRequest $request ) {
		return $request->getCookie( 'UserName' );
	}

}
