<?php

namespace MediaWiki\Tests\Revision;

use ActorMigration;
use CommentStore;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreFactory;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\Storage\SqlBlobStore;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use WANObjectCache;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\TestingAccessWrapper;

class RevisionStoreFactoryTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Revision\RevisionStoreFactory::__construct
	 */
	public function testValidConstruction_doesntCauseErrors() {
		new RevisionStoreFactory(
			$this->getMockLoadBalancerFactory(),
			$this->getMockBlobStoreFactory(),
			$this->getNameTableStoreFactory(),
			$this->getMockSlotRoleRegistry(),
			$this->getHashWANObjectCache(),
			$this->getMockCommentStore(),
			ActorMigration::newMigration(),
			new NullLogger(),
			$this->getContentHandlerFactory(),
			$this->createHookContainer()
		);
		$this->assertTrue( true );
	}

	public function provideWikiIds() {
		yield [ true ];
		yield [ false ];
		yield [ 'somewiki' ];
	}

	/**
	 * @dataProvider provideWikiIds
	 * @covers \MediaWiki\Revision\RevisionStoreFactory::getRevisionStore
	 */
	public function testGetRevisionStore( $dbDomain ) {
		$lbFactory = $this->getMockLoadBalancerFactory();
		$blobStoreFactory = $this->getMockBlobStoreFactory();
		$nameTableStoreFactory = $this->getNameTableStoreFactory();
		$slotRoleRegistry = $this->getMockSlotRoleRegistry();
		$cache = $this->getHashWANObjectCache();
		$commentStore = $this->getMockCommentStore();
		$actorMigration = ActorMigration::newMigration();
		$logger = new NullLogger();
		$contentHandlerFactory = $this->getContentHandlerFactory();
		$hookContainer = $this->createHookContainer();

		$factory = new RevisionStoreFactory(
			$lbFactory,
			$blobStoreFactory,
			$nameTableStoreFactory,
			$slotRoleRegistry,
			$cache,
			$commentStore,
			$actorMigration,
			$logger,
			$contentHandlerFactory,
			$hookContainer
		);

		$store = $factory->getRevisionStore( $dbDomain );
		$wrapper = TestingAccessWrapper::newFromObject( $store );

		// ensure the correct object type is returned
		$this->assertInstanceOf( RevisionStore::class, $store );

		// ensure the RevisionStore is for the given wikiId
		$this->assertSame( $dbDomain, $wrapper->dbDomain );

		// ensure all other required services are correctly set
		$this->assertSame( $cache, $wrapper->cache );
		$this->assertSame( $commentStore, $wrapper->commentStore );
		$this->assertSame( $actorMigration, $wrapper->actorMigration );

		$this->assertInstanceOf( ILoadBalancer::class, $wrapper->loadBalancer );
		$this->assertInstanceOf( BlobStore::class, $wrapper->blobStore );
		$this->assertInstanceOf( NameTableStore::class, $wrapper->contentModelStore );
		$this->assertInstanceOf( NameTableStore::class, $wrapper->slotRoleStore );
		$this->assertInstanceOf( LoggerInterface::class, $wrapper->logger );
	}

	/**
	 * @return MockObject|ILoadBalancer
	 */
	private function getMockLoadBalancer() {
		return $this->getMockBuilder( ILoadBalancer::class )
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return MockObject|ILBFactory
	 */
	private function getMockLoadBalancerFactory() {
		$mock = $this->getMockBuilder( ILBFactory::class )
			->disableOriginalConstructor()->getMock();

		$mock->method( 'getMainLB' )
			->willReturnCallback( function () {
				return $this->getMockLoadBalancer();
			} );

		return $mock;
	}

	/**
	 * @return MockObject|SqlBlobStore
	 */
	private function getMockSqlBlobStore() {
		return $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return MockObject|BlobStoreFactory
	 */
	private function getMockBlobStoreFactory() {
		$mock = $this->getMockBuilder( BlobStoreFactory::class )
			->disableOriginalConstructor()->getMock();

		$mock->method( 'newSqlBlobStore' )
			->willReturnCallback( function () {
				return $this->getMockSqlBlobStore();
			} );

		return $mock;
	}

	/**
	 * @return SlotRoleRegistry
	 */
	private function getMockSlotRoleRegistry() {
		return $this->createMock( SlotRoleRegistry::class );
	}

	/**
	 * @return IContentHandlerFactory|MockObject
	 */
	private function getContentHandlerFactory(): IContentHandlerFactory {
		return $this->createMock( IContentHandlerFactory::class );
	}

	/**
	 * @return NameTableStoreFactory
	 */
	private function getNameTableStoreFactory() {
		return new NameTableStoreFactory(
			$this->getMockLoadBalancerFactory(),
			$this->getHashWANObjectCache(),
			new NullLogger() );
	}

	/**
	 * @return MockObject|CommentStore
	 */
	private function getMockCommentStore() {
		return $this->getMockBuilder( CommentStore::class )
			->disableOriginalConstructor()->getMock();
	}

	private function getHashWANObjectCache() {
		return new WANObjectCache( [ 'cache' => new \HashBagOStuff() ] );
	}
}
