<?php

namespace MediaWiki\Tests\Revision;

use CommentStore;
use InvalidArgumentException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\MaintainableDBConnRef;
use MediaWikiTestCase;
use MWException;
use WANObjectCache;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * Tests RevisionStore
 */
class RevisionStoreTest extends MediaWikiTestCase {

	/**
	 * @param LoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param WANObjectCache $WANObjectCache
	 *
	 * @return RevisionStore
	 */
	private function getRevisionStore(
		$loadBalancer = null,
		$blobStore = null,
		$WANObjectCache = null
	) {
		global $wgMultiContentRevisionSchemaMigrationStage;
		// the migration stage should be irrelevant, since all the tests that interact with
		// the database are in RevisionStoreDbTest, not here.

		return new RevisionStore(
			$loadBalancer ?: $this->getMockLoadBalancer(),
			$blobStore ?: $this->getMockSqlBlobStore(),
			$WANObjectCache ?: $this->getHashWANObjectCache(),
			MediaWikiServices::getInstance()->getCommentStore(),
			MediaWikiServices::getInstance()->getContentModelStore(),
			MediaWikiServices::getInstance()->getSlotRoleStore(),
			MediaWikiServices::getInstance()->getSlotRoleRegistry(),
			$wgMultiContentRevisionSchemaMigrationStage,
			MediaWikiServices::getInstance()->getActorMigration()
		);
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer() {
		return $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|IDatabase
	 */
	private function getMockDatabase() {
		return $this->getMockBuilder( IDatabase::class )
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * @param ILoadBalancer $mockLoadBalancer
	 * @param Database $db
	 * @return callable
	 */
	private function getMockDBConnRefCallback( ILoadBalancer $mockLoadBalancer, IDatabase $db ) {
		return function ( $i, $g, $domain, $flg ) use ( $mockLoadBalancer, $db ) {
			return new MaintainableDBConnRef( $mockLoadBalancer, $db, $i );
		};
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

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|SlotRoleRegistry
	 */
	private function getMockSlotRoleRegistry() {
		return $this->getMockBuilder( SlotRoleRegistry::class )
			->disableOriginalConstructor()->getMock();
	}

	private function getHashWANObjectCache() {
		return new WANObjectCache( [ 'cache' => new \HashBagOStuff() ] );
	}

	public function provideSetContentHandlerUseDB() {
		return [
			// ContentHandlerUseDB can be true or false pre migration.
			// During and after migration it can not be false...
			[ false, SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, true ],
			[ false, SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, true ],
			[ false, SCHEMA_COMPAT_NEW, true ],
			// ...but it can be true.
			[ true, SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, false ],
			[ true, SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, false ],
			[ true, SCHEMA_COMPAT_NEW, false ],
		];
	}

	/**
	 * @dataProvider provideSetContentHandlerUseDB
	 * @covers \MediaWiki\Revision\RevisionStore::getContentHandlerUseDB
	 * @covers \MediaWiki\Revision\RevisionStore::setContentHandlerUseDB
	 */
	public function testSetContentHandlerUseDB( $contentHandlerDb, $migrationMode, $expectedFail ) {
		if ( $expectedFail ) {
			$this->setExpectedException( MWException::class );
		}

		$nameTables = MediaWikiServices::getInstance()->getNameTableStoreFactory();

		$store = new RevisionStore(
			$this->getMockLoadBalancer(),
			$this->getMockSqlBlobStore(),
			$this->getHashWANObjectCache(),
			$this->getMockCommentStore(),
			$nameTables->getContentModels(),
			$nameTables->getSlotRoles(),
			$this->getMockSlotRoleRegistry(),
			$migrationMode,
			MediaWikiServices::getInstance()->getActorMigration()
		);

		$store->setContentHandlerUseDB( $contentHandlerDb );
		$this->assertSame( $contentHandlerDb, $store->getContentHandlerUseDB() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromPageId() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		// RevisionStore uses getConnectionRef
		$mockLoadBalancer->expects( $this->any() )
			->method( 'getConnectionRef' )
			->willReturnCallback( $this->getMockDBConnRefCallback( $mockLoadBalancer, $db ) );
		// Title calls wfGetDB() which uses getMaintenanceConnectionRef
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getMaintenanceConnectionRef' )
			->willReturnCallback( $this->getMockDBConnRefCallback( $mockLoadBalancer, $db ) );

		// First call to Title::newFromID, faking no result (db lag?)
		$db->expects( $this->at( 0 ) )
			->method( 'selectRow' )
			->with(
				'page',
				$this->anything(),
				[ 'page_id' => 1 ]
			)
			->willReturn( (object)[
				'page_namespace' => '1',
				'page_title' => 'Food',
			] );

		$store = $this->getRevisionStore( $mockLoadBalancer );
		$title = $store->getTitle( 1, 2, RevisionStore::READ_NORMAL );

		$this->assertSame( 1, $title->getNamespace() );
		$this->assertSame( 'Food', $title->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromPageIdOnFallback() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		// Title calls wfGetDB() which uses getMaintenanceConnectionRef
		// Assert that the first call uses a REPLICA and the second falls back to master
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getConnectionRef' )
			->willReturnCallback( $this->getMockDBConnRefCallback( $mockLoadBalancer, $db ) );
		// Title calls wfGetDB() which uses getMaintenanceConnectionRef
		$mockLoadBalancer->expects( $this->exactly( 2 ) )
			->method( 'getMaintenanceConnectionRef' )
			->willReturnCallback( $this->getMockDBConnRefCallback( $mockLoadBalancer, $db ) );

		// First call to Title::newFromID, faking no result (db lag?)
		$db->expects( $this->at( 0 ) )
			->method( 'selectRow' )
			->with(
				'page',
				$this->anything(),
				[ 'page_id' => 1 ]
			)
			->willReturn( false );

		// First select using rev_id, faking no result (db lag?)
		$db->expects( $this->at( 1 ) )
			->method( 'selectRow' )
			->with(
				[ 'revision', 'page' ],
				$this->anything(),
				[ 'rev_id' => 2 ]
			)
			->willReturn( false );

		// Second call to Title::newFromID, no result
		$db->expects( $this->at( 2 ) )
			->method( 'selectRow' )
			->with(
				'page',
				$this->anything(),
				[ 'page_id' => 1 ]
			)
			->willReturn( (object)[
				'page_namespace' => '2',
				'page_title' => 'Foodey',
			] );

		$store = $this->getRevisionStore( $mockLoadBalancer );
		$title = $store->getTitle( 1, 2, RevisionStore::READ_NORMAL );

		$this->assertSame( 2, $title->getNamespace() );
		$this->assertSame( 'Foodey', $title->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromRevId() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getConnectionRef' )
			->willReturnCallback( $this->getMockDBConnRefCallback( $mockLoadBalancer, $db ) );
		// Title calls wfGetDB() which uses getMaintenanceConnectionRef
		// RevisionStore getTitle uses getMaintenanceConnectionRef
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getMaintenanceConnectionRef' )
			->willReturnCallback( $this->getMockDBConnRefCallback( $mockLoadBalancer, $db ) );

		// First call to Title::newFromID, faking no result (db lag?)
		$db->expects( $this->at( 0 ) )
			->method( 'selectRow' )
			->with(
				'page',
				$this->anything(),
				[ 'page_id' => 1 ]
			)
			->willReturn( false );

		// First select using rev_id, faking no result (db lag?)
		$db->expects( $this->at( 1 ) )
			->method( 'selectRow' )
			->with(
				[ 'revision', 'page' ],
				$this->anything(),
				[ 'rev_id' => 2 ]
			)
			->willReturn( (object)[
				'page_namespace' => '1',
				'page_title' => 'Food2',
			] );

		$store = $this->getRevisionStore( $mockLoadBalancer );
		$title = $store->getTitle( 1, 2, RevisionStore::READ_NORMAL );

		$this->assertSame( 1, $title->getNamespace() );
		$this->assertSame( 'Food2', $title->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromRevIdOnFallback() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		// Assert that the first call uses a REPLICA and the second falls back to master
		// RevisionStore uses getMaintenanceConnectionRef
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getConnectionRef' )
			->willReturnCallback( $this->getMockDBConnRefCallback( $mockLoadBalancer, $db ) );
		// Title calls wfGetDB() which uses getMaintenanceConnectionRef
		$mockLoadBalancer->expects( $this->exactly( 2 ) )
			->method( 'getMaintenanceConnectionRef' )
			->willReturnCallback( $this->getMockDBConnRefCallback( $mockLoadBalancer, $db ) );

		// First call to Title::newFromID, faking no result (db lag?)
		$db->expects( $this->at( 0 ) )
			->method( 'selectRow' )
			->with(
				'page',
				$this->anything(),
				[ 'page_id' => 1 ]
			)
			->willReturn( false );

		// First select using rev_id, faking no result (db lag?)
		$db->expects( $this->at( 1 ) )
			->method( 'selectRow' )
			->with(
				[ 'revision', 'page' ],
				$this->anything(),
				[ 'rev_id' => 2 ]
			)
			->willReturn( false );

		// Second call to Title::newFromID, no result
		$db->expects( $this->at( 2 ) )
			->method( 'selectRow' )
			->with(
				'page',
				$this->anything(),
				[ 'page_id' => 1 ]
			)
			->willReturn( false );

		// Second select using rev_id, result
		$db->expects( $this->at( 3 ) )
			->method( 'selectRow' )
			->with(
				[ 'revision', 'page' ],
				$this->anything(),
				[ 'rev_id' => 2 ]
			)
			->willReturn( (object)[
				'page_namespace' => '2',
				'page_title' => 'Foodey',
			] );

		$store = $this->getRevisionStore( $mockLoadBalancer );
		$title = $store->getTitle( 1, 2, RevisionStore::READ_NORMAL );

		$this->assertSame( 2, $title->getNamespace() );
		$this->assertSame( 'Foodey', $title->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_correctFallbackAndthrowsExceptionAfterFallbacks() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		// Title calls wfGetDB() which uses getMaintenanceConnectionRef
		// Assert that the first call uses a REPLICA and the second falls back to master

		// RevisionStore getTitle uses getConnectionRef
		// Title::newFromID uses getMaintenanceConnectionRef
		foreach ( [
			'getConnectionRef', 'getMaintenanceConnectionRef'
		] as $method ) {
			$mockLoadBalancer->expects( $this->exactly( 2 ) )
				->method( $method )
				->willReturnCallback( function ( $masterOrReplica ) use ( $db ) {
					static $callCounter = 0;
					$callCounter++;
					// The first call should be to a REPLICA, and the second a MASTER.
					if ( $callCounter === 1 ) {
						$this->assertSame( DB_REPLICA, $masterOrReplica );
					} elseif ( $callCounter === 2 ) {
						$this->assertSame( DB_MASTER, $masterOrReplica );
					}
					return $db;
				} );
		}
		// First and third call to Title::newFromID, faking no result
		foreach ( [ 0, 2 ] as $counter ) {
			$db->expects( $this->at( $counter ) )
				->method( 'selectRow' )
				->with(
					'page',
					$this->anything(),
					[ 'page_id' => 1 ]
				)
				->willReturn( false );
		}

		foreach ( [ 1, 3 ] as $counter ) {
			$db->expects( $this->at( $counter ) )
				->method( 'selectRow' )
				->with(
					[ 'revision', 'page' ],
					$this->anything(),
					[ 'rev_id' => 2 ]
				)
				->willReturn( false );
		}

		$store = $this->getRevisionStore( $mockLoadBalancer );

		$this->setExpectedException( RevisionAccessException::class );
		$store->getTitle( 1, 2, RevisionStore::READ_NORMAL );
	}

	public function provideMigrationConstruction() {
		return [
			[ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, false ],
			[ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, false ],
			[ SCHEMA_COMPAT_NEW, false ],
			[ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_BOTH, true ],
			[ SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_READ_BOTH, true ],
			[ SCHEMA_COMPAT_WRITE_NEW | SCHEMA_COMPAT_READ_BOTH, true ],
		];
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::__construct
	 * @dataProvider provideMigrationConstruction
	 */
	public function testMigrationConstruction( $migration, $expectException ) {
		if ( $expectException ) {
			$this->setExpectedException( InvalidArgumentException::class );
		}
		$loadBalancer = $this->getMockLoadBalancer();
		$blobStore = $this->getMockSqlBlobStore();
		$cache = $this->getHashWANObjectCache();
		$commentStore = $this->getMockCommentStore();
		$services = MediaWikiServices::getInstance();
		$nameTables = $services->getNameTableStoreFactory();
		$contentModelStore = $nameTables->getContentModels();
		$slotRoleStore = $nameTables->getSlotRoles();
		$slotRoleRegistry = $services->getSlotRoleRegistry();
		$store = new RevisionStore(
			$loadBalancer,
			$blobStore,
			$cache,
			$commentStore,
			$nameTables->getContentModels(),
			$nameTables->getSlotRoles(),
			$slotRoleRegistry,
			$migration,
			$services->getActorMigration()
		);
		if ( !$expectException ) {
			$store = TestingAccessWrapper::newFromObject( $store );
			$this->assertSame( $loadBalancer, $store->loadBalancer );
			$this->assertSame( $blobStore, $store->blobStore );
			$this->assertSame( $cache, $store->cache );
			$this->assertSame( $commentStore, $store->commentStore );
			$this->assertSame( $contentModelStore, $store->contentModelStore );
			$this->assertSame( $slotRoleStore, $store->slotRoleStore );
			$this->assertSame( $migration, $store->mcrMigrationStage );
		}
	}

}
