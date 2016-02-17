<?php

namespace MediaWiki\Session;

/**
 * BagOStuff with utility functions for MediaWiki\\Session\\* testing
 */
class TestBagOStuff extends \CachedBagOStuff {

	public function __construct() {
		parent::__construct( new \HashBagOStuff );
	}

	/**
	 * @param string $id Session ID
	 * @param array $data Session data
	 * @param int $expiry Expiry
	 * @param User $user User for metadata
	 */
	public function setSessionData( $id, array $data, $expiry = 0, User $user = null ) {
		$this->setSession( $id, [ 'data' => $data ], $expiry, $user );
	}

	/**
	 * @param string $id Session ID
	 * @param array $metadata Session metadata
	 * @param int $expiry Expiry
	 */
	public function setSessionMeta( $id, array $metadata, $expiry = 0 ) {
		$this->setSession( $id, [ 'metadata' => $metadata ], $expiry );
	}

	/**
	 * @param string $id Session ID
	 * @param array $blob Session metadata and data
	 * @param int $expiry Expiry
	 * @param User $user User for metadata
	 */
	public function setSession( $id, array $blob, $expiry = 0, User $user = null ) {
		$blob += [
			'data' => [],
			'metadata' => [],
		];
		$blob['metadata'] += [
			'userId' => $user ? $user->getId() : 0,
			'userName' => $user ? $user->getName() : null,
			'userToken' => $user ? $user->getToken( true ) : null,
			'provider' => 'DummySessionProvider',
		];

		$this->setRawSession( $id, $blob, $expiry, $user );
	}

	/**
	 * @param string $id Session ID
	 * @param array|mixed $blob Session metadata and data
	 * @param int $expiry Expiry
	 */
	public function setRawSession( $id, $blob, $expiry = 0 ) {
		if ( $expiry <= 0 ) {
			$expiry = \RequestContext::getMain()->getConfig()->get( 'ObjectCacheSessionExpiry' );
		}

		$this->set( wfMemcKey( 'MWSession', $id ), $blob, $expiry );
	}

	/**
	 * @param string $id Session ID
	 * @return mixed
	 */
	public function getSession( $id ) {
		return $this->get( wfMemcKey( 'MWSession', $id ) );
	}

	/**
	 * @param string $id Session ID
	 * @return mixed
	 */
	public function getSessionFromBackend( $id ) {
		return $this->backend->get( wfMemcKey( 'MWSession', $id ) );
	}

	/**
	 * @param string $id Session ID
	 */
	public function deleteSession( $id ) {
		$this->delete( wfMemcKey( 'MWSession', $id ) );
	}

}
