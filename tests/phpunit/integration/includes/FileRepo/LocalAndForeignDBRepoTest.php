<?php

use MediaWiki\FileRepo\ForeignDBViaLBRepo;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;

class LocalAndForeignDBRepoTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \MediaWiki\FileRepo\LocalRepo::getSharedCacheKey
	 * @covers \MediaWiki\FileRepo\ForeignDBViaLBRepo::getSharedCacheKey
	 */
	public function testsSharedCacheKey() {
		$wikiId = WikiMap::getCurrentWikiDbDomain()->getId();
		$wanCache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$localRepo = new LocalRepo( [
			'name' => 'local',
			'backend' => 'local-backend',
			'wanCache' => $wanCache
		] );
		$foreignRepo = new ForeignDBViaLBRepo( [
			'name' => 'local',
			'backend' => 'local-backend',
			'wiki' => $wikiId,
			'hasSharedCache' => true,
			'wanCache' => $wanCache
		] );
		$foreignRepo2 = new ForeignDBViaLBRepo( [
			'name' => 'local',
			'backend' => 'local-backend',
			'wiki' => 'weirdoutside-domain',
			'hasSharedCache' => true,
			'wanCache' => $wanCache
		] );

		$sharedKeyForLocal = $localRepo->getSharedCacheKey( 'class', 93 );
		$sharedKeyForForeign = $foreignRepo->getSharedCacheKey( 'class', 93 );
		$sharedKeyForForiegn2 = $foreignRepo2->getSharedCacheKey( 'class', 93 );

		$this->assertSame(
			$wanCache->makeGlobalKey( 'filerepo-class', $wikiId, 93 ),
			$sharedKeyForLocal,
			"Shared key (repo is on local domain)"
		);
		$this->assertSame(
			$sharedKeyForLocal,
			$sharedKeyForForeign,
			"Shared key (repo is on foreign domain)"
		);

		$this->assertSame(
			$wanCache->makeGlobalKey( 'filerepo-class', 'weirdoutside-domain', 93 ),
			$sharedKeyForForiegn2,
			"Shared key (repo is on a different foreign domain)"
		);
	}

	/**
	 * @covers \MediaWiki\FileRepo\LocalRepo::getLocalCacheKey
	 * @covers \MediaWiki\FileRepo\ForeignDBViaLBRepo::getLocalCacheKey
	 */
	public function testsLocalCacheKey() {
		$wikiId = WikiMap::getCurrentWikiDbDomain()->getId();
		$wanCache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$localRepo = new LocalRepo( [
			'name' => 'local',
			'backend' => 'local-backend',
			'wanCache' => $wanCache
		] );
		$foreignRepo = new ForeignDBViaLBRepo( [
			'name' => 'local',
			'backend' => 'local-backend',
			'wiki' => $wikiId,
			'hasSharedCache' => true,
			'wanCache' => $wanCache
		] );

		$nonsharedKeyForLocal = $localRepo->getLocalCacheKey( 'class', 93 );
		$nonsharedKeyForForeign = $foreignRepo->getLocalCacheKey( 'class', 93 );

		$this->assertSame(
			$wanCache->makeKey( 'filerepo-class', 'local', 93 ),
			$nonsharedKeyForLocal,
			"Non-shared key (repo is on local domain)"
		);
		$this->assertSame(
			$wanCache->makeKey( 'filerepo-class', 'local', 93 ),
			$nonsharedKeyForForeign,
			"Non-shared key (repo is on local domain)"
		);
	}
}
