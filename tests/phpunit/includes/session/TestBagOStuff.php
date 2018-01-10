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
	 */
	public function setSessionData( $id, array $data ) {
		$this->setSession( $id, [ 'data' => $data ] );
	}

	/**
	 * @param string $id Session ID
	 * @param array $metadata Session metadata
	 */
	public function setSessionMeta( $id, array $metadata ) {
		$this->setSession( $id, [ 'metadata' => $metadata ] );
	}

	/**
	 * @param string $id Session ID
	 * @param array $blob Session metadata and data
	 */
	public function setSession( $id, array $blob ) {
		$blob += [
			'data' => [],
			'metadata' => [],
		];
		$blob['metadata'] += [
			'userId' => 0,
			'userName' => null,
			'userToken' => null,
			'provider' => 'DummySessionProvider',
		];

		$this->setRawSession( $id, $blob );
	}

	/**
	 * @param string $id Session ID
	 * @param array|mixed $blob Session metadata and data
	 */
	public function setRawSession( $id, $blob ) {
		$expiry = \RequestContext::getMain()->getConfig()->get( 'ObjectCacheSessionExpiry' );
		$this->set( $this->makeKey( 'MWSession', $id ), $blob, $expiry );
	}

	/**
	 * @param string $id Session ID
	 * @return mixed
	 */
	public function getSession( $id ) {
		return $this->get( $this->makeKey( 'MWSession', $id ) );
	}

	/**
	 * @param string $id Session ID
	 * @return mixed
	 */
	public function getSessionFromBackend( $id ) {
		return $this->backend->get( $this->makeKey( 'MWSession', $id ) );
	}

	/**
	 * @param string $id Session ID
	 */
	public function deleteSession( $id ) {
		$this->delete( $this->makeKey( 'MWSession', $id ) );
	}

}
