<?php
use MediaWiki\Session\SessionProvider;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionBackend;

/**
 * Dummy session provider
 *
 * An implementation of a session provider that doesn't actually do anything.
 */
class DummySessionProvider extends SessionProvider {

	const ID = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

	public static function populateStore( BagOStuff $store, $data, $user = null ) {
		$expiry = RequestContext::getMain()->getConfig()->get( 'ObjectCacheSessionExpiry' );
		$store->set( wfMemcKey( 'MWSession', 'data', self::ID ), $data, $expiry );
		$store->set( wfMemcKey( 'MWSession', 'metadata', self::ID ), array(
			'userId' => $user ? $user->getId() : 0,
			'userName' => $user ? $user->getName() : 0,
			'userToken' => $user ? $user->getToken( true ) : 0,
			'provider' => __CLASS__,
		), $expiry );
	}

	public function provideSessionInfo( WebRequest $request ) {
		return new SessionInfo( SessionInfo::MIN_PRIORITY, array(
			'provider' => $this,
			'id' => self::ID,
		) );
	}

	public function persistsSessionId() {
		return true;
	}

	public function persistSession( SessionBackend $session, WebRequest $request ) {
	}

	public function unpersistSession( WebRequest $request ) {
	}

	public function immutableSessionCouldExistForUser( $username ) {
		return false;
	}

	public function preventImmutableSessionsForUser( $username ) {
	}

	public function suggestLoginUsername( WebRequest $request ) {
		return $request->getCookie( 'UserName' );
	}

}
