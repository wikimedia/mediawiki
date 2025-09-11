<?php

namespace MediaWiki\Tests\Session;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Session\SessionInfo;

trait SessionStoreTestTrait {

	private function getHookContainer(): HookContainer {
		return $this->getServiceContainer()->getHookContainer();
	}

	/**
	 * @return mixed
	 */
	private function getSession( string $id ) {
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'id' => $id ] );

		return $this->store->get( $info );
	}

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
