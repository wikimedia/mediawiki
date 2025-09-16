<?php

namespace MediaWiki\Tests\Session;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Session\SessionInfo;

/**
 * Utility for performing various operations on a session store backend during
 * tests like getting, setting, and deleting session data.
 */
trait SessionStoreTestTrait {

	/**
	 * @param string $id Session ID
	 * @return mixed Store value for a given session ID
	 */
	private function getSession( string $id ) {
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'id' => $id ] );

		return $this->store->get( $info );
	}

	/**
	 * @param array{data?:array,metadata?:array} $blob Raw session data to be set for a given session.
	 * @param SessionInfo|null $info
	 * @return void
	 */
	private function setSessionBlob( array $blob, ?SessionInfo $info = null ): void {
		$info ??= new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this->provider,
			'id' => self::SESSIONID,
			'persisted' => true,
			'idIsSafe' => true,
		] );

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

		$expiry = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::ObjectCacheSessionExpiry );
		$this->store->set( $info, $blob, $expiry );
	}

	/**
	 * @param string $id Session ID of the session data to be deleted
	 * @return void
	 */
	private function deleteSession( string $id ): void {
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [
			'provider' => $this->provider,
			'id' => $id,
			'persisted' => true,
			'idIsSafe' => true,
		] );

		$this->store->delete( $info );
	}
}
