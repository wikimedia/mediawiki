<?php

namespace MediaWiki\Tests\Storage;

use ActorMigration;
use CommentStore;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\RevisionStoreFactory;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use WANObjectCache;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

class RevisionStoreFactoryTest extends MediaWikiTestCase {

	public function testValidConstruction_doesntCauseErrors() {
		new RevisionStoreFactory(
			$this->getMockLoadBalancer(),
			$this->getMockSqlBlobStore(),
			$this->getHashWANObjectCache(),
			$this->getMockCommentStore(),
			$this->getMockNameTableStore(),
			$this->getMockNameTableStore(),
			MIGRATION_OLD,
			ActorMigration::newMigration(),
			LoggerFactory::getInstance( 'someInstance' ),
			true
		);
		$this->assertTrue( true );
	}

	public function provideWikiIds() {
		yield [ true ];
		yield [ false ];
		yield [ 'somewiki' ];
		yield [ 'somewiki', MIGRATION_OLD , false ];
		yield [ 'somewiki', MIGRATION_NEW , true ];
	}

	/**
	 * @dataProvider provideWikiIds
	 */
	public function testGetRevisionStore(
		$wikiId,
		$mcrMigrationStage = MIGRATION_OLD,
		$contentHandlerUseDb = true
	) {
		$lb = $this->getMockLoadBalancer();
		$blobStore = $this->getMockSqlBlobStore();
		$cache = $this->getHashWANObjectCache();
		$commentStore = $this->getMockCommentStore();
		$contentModelStore = $this->getMockNameTableStore();
		$slotRoleStore = $this->getMockNameTableStore();
		$actorMigration = ActorMigration::newMigration();
		$logger = LoggerFactory::getInstance( 'someInstance' );

		$factory = new RevisionStoreFactory(
			$lb,
			$blobStore,
			$cache,
			$commentStore,
			$contentModelStore,
			$slotRoleStore,
			$mcrMigrationStage,
			$actorMigration,
			$logger,
			$contentHandlerUseDb
		);

		$store = $factory->getRevisionStore( $wikiId );
		$wrapper = TestingAccessWrapper::newFromObject( $store );

		// ensure the correct object type is returned
		$this->assertInstanceOf( RevisionStore::class, $store );

		// ensure the RevisionStore is for the given wikiId
		$this->assertSame( $wikiId, $wrapper->wikiId );

		// ensure all other required services are correctly set
		$this->assertSame( $lb, $wrapper->loadBalancer );
		$this->assertSame( $blobStore, $wrapper->blobStore );
		$this->assertSame( $cache, $wrapper->cache );
		$this->assertSame( $commentStore, $wrapper->commentStore );
		$this->assertSame( $contentModelStore, $wrapper->contentModelStore );
		$this->assertSame( $slotRoleStore, $wrapper->slotRoleStore );
		$this->assertSame( $mcrMigrationStage, $wrapper->mcrMigrationStage );
		$this->assertSame( $actorMigration, $wrapper->actorMigration );
		$this->assertSame( $logger, $wrapper->logger );
		$this->assertSame( $contentHandlerUseDb, $store->getContentHandlerUseDB() );
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|NameTableStore
	 */
	private function getMockNameTableStore() {
		return $this->getMockBuilder( NameTableStore::class )
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer() {
		return $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|SqlBlobStore
	 */
	private function getMockSqlBlobStore() {
		return $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|CommentStore
	 */
	private function getMockCommentStore() {
		return $this->getMockBuilder( CommentStore::class )
			->disableOriginalConstructor()->getMock();
	}

	private function getHashWANObjectCache() {
		return new WANObjectCache( [ 'cache' => new \HashBagOStuff() ] );
	}

}
