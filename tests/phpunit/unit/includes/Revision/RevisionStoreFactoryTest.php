<?php

namespace MediaWiki\Tests\Unit\Revision;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\PageStoreFactory;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreFactory;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserIdentityLookup;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use WANObjectCache;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Revision\RevisionStoreFactory
 */
class RevisionStoreFactoryTest extends MediaWikiUnitTestCase {

	public function testValidConstruction_doesntCauseErrors() {
		new RevisionStoreFactory(
			$this->getMockLoadBalancerFactory(),
			$this->getMockBlobStoreFactory(),
			$this->getNameTableStoreFactory(),
			$this->createMock( SlotRoleRegistry::class ),
			$this->getHashWANObjectCache(),
			new HashBagOStuff(),
			$this->createMock( CommentStore::class ),
			$this->getMockActorStoreFactory(),
			new NullLogger(),
			$this->createMock( IContentHandlerFactory::class ),
			$this->getPageStoreFactory(),
			$this->createMock( TitleFactory::class ),
			$this->createHookContainer()
		);
		$this->assertTrue( true );
	}

	public static function provideWikiIds() {
		yield [ false ];
		yield [ 'somewiki' ];
	}

	/**
	 * @dataProvider provideWikiIds
	 */
	public function testGetRevisionStore( $wikiId ) {
		$cache = $this->getHashWANObjectCache();
		$commentStore = $this->createMock( CommentStore::class );

		$factory = new RevisionStoreFactory(
			$this->getMockLoadBalancerFactory(),
			$this->getMockBlobStoreFactory(),
			$this->getNameTableStoreFactory(),
			$this->createMock( SlotRoleRegistry::class ),
			$cache,
			new HashBagOStuff(),
			$commentStore,
			$this->getMockActorStoreFactory(),
			new NullLogger(),
			$this->createMock( IContentHandlerFactory::class ),
			$this->getPageStoreFactory(),
			$this->createMock( TitleFactory::class ),
			$this->createHookContainer()
		);

		$store = $factory->getRevisionStore( $wikiId );
		$wrapper = TestingAccessWrapper::newFromObject( $store );

		// ensure the correct object type is returned
		$this->assertInstanceOf( RevisionStore::class, $store );

		// ensure the RevisionStore is for the given wikiId
		$this->assertSame( $wikiId, $wrapper->wikiId );

		// ensure all other required services are correctly set
		$this->assertSame( $cache, $wrapper->cache );
		$this->assertSame( $commentStore, $wrapper->commentStore );

		$this->assertInstanceOf( ILoadBalancer::class, $wrapper->loadBalancer );
		$this->assertInstanceOf( BlobStore::class, $wrapper->blobStore );
		$this->assertInstanceOf( NameTableStore::class, $wrapper->contentModelStore );
		$this->assertInstanceOf( NameTableStore::class, $wrapper->slotRoleStore );
		$this->assertInstanceOf( LoggerInterface::class, $wrapper->logger );
		$this->assertInstanceOf( UserIdentityLookup::class, $wrapper->actorStore );
	}

	/**
	 * @return MockObject|ILBFactory
	 */
	private function getMockLoadBalancerFactory() {
		$mock = $this->createMock( ILBFactory::class );
		$mock->method( 'getMainLB' )
			->willReturn( $this->createMock( ILoadBalancer::class ) );

		return $mock;
	}

	/**
	 * @return MockObject|BlobStoreFactory
	 */
	private function getMockBlobStoreFactory() {
		$mock = $this->createMock( BlobStoreFactory::class );

		$mock->method( 'newSqlBlobStore' )
			->willReturn( $this->createMock( SqlBlobStore::class ) );

		return $mock;
	}

	/**
	 * @return PageStoreFactory|MockObject
	 */
	private function getPageStoreFactory(): PageStoreFactory {
		$mock = $this->createMock( PageStoreFactory::class );

		$mock->method( 'getPageStore' )
			->willReturn( $this->createMock( PageStore::class ) );

		return $mock;
	}

	/**
	 * @return NameTableStoreFactory
	 */
	private function getNameTableStoreFactory() {
		return new NameTableStoreFactory(
			$this->getMockLoadBalancerFactory(),
			$this->getHashWANObjectCache(),
			new NullLogger()
		);
	}

	/**
	 * @return WANObjectCache
	 */
	private function getHashWANObjectCache() {
		return new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
	}

	/**
	 * @return ActorStoreFactory|MockObject
	 */
	private function getMockActorStoreFactory() {
		$mock = $this->createMock( ActorStoreFactory::class );

		$mock->method( 'getActorStore' )
			->willReturn( $this->createMock( ActorStore::class ) );

		return $mock;
	}
}
