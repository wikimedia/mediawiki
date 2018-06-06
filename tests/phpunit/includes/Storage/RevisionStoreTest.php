<?php

namespace MediaWiki\Tests\Storage;

use CommentStore;
use HashBagOStuff;
use InvalidArgumentException;
use Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use MWException;
use Title;
use WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

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
			$loadBalancer ? $loadBalancer : $this->getMockLoadBalancer(),
			$blobStore ? $blobStore : $this->getMockSqlBlobStore(),
			$WANObjectCache ? $WANObjectCache : $this->getHashWANObjectCache(),
			MediaWikiServices::getInstance()->getCommentStore(),
			MediaWikiServices::getInstance()->getContentModelStore(),
			MediaWikiServices::getInstance()->getSlotRoleStore(),
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
	 * @return \PHPUnit_Framework_MockObject_MockObject|Database
	 */
	private function getMockDatabase() {
		return $this->getMockBuilder( Database::class )
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

	public function provideSetContentHandlerUseDB() {
		return [
			// ContentHandlerUseDB can be true of false pre migration
			[ false, MIGRATION_OLD, false ],
			[ true, MIGRATION_OLD, false ],
			// During migration it can not be false
			[ false, MIGRATION_WRITE_BOTH, true ],
			// But it can be true
			[ true, MIGRATION_WRITE_BOTH, false ],
		];
	}

	/**
	 * @dataProvider provideSetContentHandlerUseDB
	 * @covers \MediaWiki\Storage\RevisionStore::getContentHandlerUseDB
	 * @covers \MediaWiki\Storage\RevisionStore::setContentHandlerUseDB
	 */
	public function testSetContentHandlerUseDB( $contentHandlerDb, $migrationMode, $expectedFail ) {
		if ( $expectedFail ) {
			$this->setExpectedException( MWException::class );
		}

		$store = new RevisionStore(
			$this->getMockLoadBalancer(),
			$this->getMockSqlBlobStore(),
			$this->getHashWANObjectCache(),
			$this->getMockCommentStore(),
			MediaWikiServices::getInstance()->getContentModelStore(),
			MediaWikiServices::getInstance()->getSlotRoleStore(),
			$migrationMode,
			MediaWikiServices::getInstance()->getActorMigration()
		);

		$store->setContentHandlerUseDB( $contentHandlerDb );
		$this->assertSame( $contentHandlerDb, $store->getContentHandlerUseDB() );
	}

	public function testGetTitle_successFromPageId() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		// Title calls wfGetDB() which uses a regular Connection
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getConnection' )
			->willReturn( $db );

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

	public function testGetTitle_successFromPageIdOnFallback() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		// Title calls wfGetDB() which uses a regular Connection
		// Assert that the first call uses a REPLICA and the second falls back to master
		$mockLoadBalancer->expects( $this->exactly( 2 ) )
			->method( 'getConnection' )
			->willReturn( $db );
		// RevisionStore getTitle uses a ConnectionRef
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getConnectionRef' )
			->willReturn( $db );

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

	public function testGetTitle_successFromRevId() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		// Title calls wfGetDB() which uses a regular Connection
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getConnection' )
			->willReturn( $db );
		// RevisionStore getTitle uses a ConnectionRef
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getConnectionRef' )
			->willReturn( $db );

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

	public function testGetTitle_successFromRevIdOnFallback() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		// Title calls wfGetDB() which uses a regular Connection
		// Assert that the first call uses a REPLICA and the second falls back to master
		$mockLoadBalancer->expects( $this->exactly( 2 ) )
			->method( 'getConnection' )
			->willReturn( $db );
		// RevisionStore getTitle uses a ConnectionRef
		$mockLoadBalancer->expects( $this->atLeastOnce() )
			->method( 'getConnectionRef' )
			->willReturn( $db );

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
	 * @covers \MediaWiki\Storage\RevisionStore::getTitle
	 */
	public function testGetTitle_correctFallbackAndthrowsExceptionAfterFallbacks() {
		$mockLoadBalancer = $this->getMockLoadBalancer();
		// Title calls wfGetDB() so we have to set the main service
		$this->setService( 'DBLoadBalancer', $mockLoadBalancer );

		$db = $this->getMockDatabase();
		// Title calls wfGetDB() which uses a regular Connection
		// Assert that the first call uses a REPLICA and the second falls back to master

		// RevisionStore getTitle uses getConnectionRef
		// Title::newFromID uses getConnection
		foreach ( [ 'getConnection', 'getConnectionRef' ] as $method ) {
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

	public function provideNewRevisionFromRow_legacyEncoding_applied() {
		yield 'windows-1252, old_flags is empty' => [
			'windows-1252',
			'en',
			[
				'old_flags' => '',
				'old_text' => "S\xF6me Content",
			],
			'Söme Content'
		];

		yield 'windows-1252, old_flags is null' => [
			'windows-1252',
			'en',
			[
				'old_flags' => null,
				'old_text' => "S\xF6me Content",
			],
			'Söme Content'
		];
	}

	/**
	 * @dataProvider provideNewRevisionFromRow_legacyEncoding_applied
	 *
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_legacyEncoding_applied( $encoding, $locale, $row, $text ) {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();

		$blobStore = new SqlBlobStore( $lb, $cache );
		$blobStore->setLegacyEncoding( $encoding, Language::factory( $locale ) );

		$store = $this->getRevisionStore( $lb, $blobStore, $cache );

		$record = $store->newRevisionFromRow(
			$this->makeRow( $row ),
			0,
			Title::newFromText( __METHOD__ . '-UTPage' )
		);

		$this->assertSame( $text, $record->getContent( 'main' )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_legacyEncoding_ignored() {
		$row = [
			'old_flags' => 'utf-8',
			'old_text' => 'Söme Content',
		];

		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();

		$blobStore = new SqlBlobStore( $lb, $cache );
		$blobStore->setLegacyEncoding( 'windows-1252', Language::factory( 'en' ) );

		$store = $this->getRevisionStore( $lb, $blobStore, $cache );

		$record = $store->newRevisionFromRow(
			$this->makeRow( $row ),
			0,
			Title::newFromText( __METHOD__ . '-UTPage' )
		);
		$this->assertSame( 'Söme Content', $record->getContent( 'main' )->serialize() );
	}

	private function makeRow( array $array ) {
		$row = $array + [
				'rev_id' => 7,
				'rev_page' => 5,
				'rev_text_id' => 11,
				'rev_timestamp' => '20110101000000',
				'rev_user_text' => 'Tester',
				'rev_user' => 17,
				'rev_minor_edit' => 0,
				'rev_deleted' => 0,
				'rev_len' => 100,
				'rev_parent_id' => 0,
				'rev_sha1' => 'deadbeef',
				'rev_comment_text' => 'Testing',
				'rev_comment_data' => '{}',
				'rev_comment_cid' => 111,
				'rev_content_format' => CONTENT_FORMAT_TEXT,
				'rev_content_model' => CONTENT_MODEL_TEXT,
				'page_namespace' => 0,
				'page_title' => 'TEST',
				'page_id' => 5,
				'page_latest' => 7,
				'page_is_redirect' => 0,
				'page_len' => 100,
				'user_name' => 'Tester',
				'old_is' => 13,
				'old_text' => 'Hello World',
				'old_flags' => 'utf-8',
			];

		return (object)$row;
	}

	public function provideMigrationConstruction() {
		return [
			[ MIGRATION_OLD, false ],
			[ MIGRATION_WRITE_BOTH, false ],
		];
	}

	/**
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
		$contentModelStore = MediaWikiServices::getInstance()->getContentModelStore();
		$slotRoleStore = MediaWikiServices::getInstance()->getSlotRoleStore();
		$store = new RevisionStore(
			$loadBalancer,
			$blobStore,
			$cache,
			$commentStore,
			MediaWikiServices::getInstance()->getContentModelStore(),
			MediaWikiServices::getInstance()->getSlotRoleStore(),
			$migration,
			MediaWikiServices::getInstance()->getActorMigration()
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
