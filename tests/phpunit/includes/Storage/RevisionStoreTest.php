<?php

namespace MediaWiki\Tests\Storage;

use HashBagOStuff;
use Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use Title;
use WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\LoadBalancer;

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
		return new RevisionStore(
			$loadBalancer ? $loadBalancer : $this->getMockLoadBalancer(),
			$blobStore ? $blobStore : $this->getMockSqlBlobStore(),
			$WANObjectCache ? $WANObjectCache : $this->getHashWANObjectCache(),
			MediaWikiServices::getInstance()->getCommentStore(),
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

	private function getHashWANObjectCache() {
		return new WANObjectCache( [ 'cache' => new \HashBagOStuff() ] );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getContentHandlerUseDB
	 * @covers \MediaWiki\Storage\RevisionStore::setContentHandlerUseDB
	 */
	public function testGetSetContentHandlerDb() {
		$store = $this->getRevisionStore();
		$this->assertTrue( $store->getContentHandlerUseDB() );
		$store->setContentHandlerUseDB( false );
		$this->assertFalse( $store->getContentHandlerUseDB() );
		$store->setContentHandlerUseDB( true );
		$this->assertTrue( $store->getContentHandlerUseDB() );
	}

	private function getDefaultQueryFields() {
		return [
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_timestamp',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
		];
	}

	private function getCommentQueryFields() {
		return [
			'rev_comment_text' => 'rev_comment',
			'rev_comment_data' => 'NULL',
			'rev_comment_cid' => 'NULL',
		];
	}

	private function getActorQueryFields() {
		return [
			'rev_user' => 'rev_user',
			'rev_user_text' => 'rev_user_text',
			'rev_actor' => 'NULL',
		];
	}

	private function getContentHandlerQueryFields() {
		return [
			'rev_content_format',
			'rev_content_model',
		];
	}

	public function provideGetQueryInfo() {
		yield [
			true,
			[],
			[
				'tables' => [ 'revision' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					$this->getContentHandlerQueryFields()
				),
				'joins' => [],
			]
		];
		yield [
			false,
			[],
			[
				'tables' => [ 'revision' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields()
				),
				'joins' => [],
			]
		];
		yield [
			false,
			[ 'page' ],
			[
				'tables' => [ 'revision', 'page' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					[
						'page_namespace',
						'page_title',
						'page_id',
						'page_latest',
						'page_is_redirect',
						'page_len',
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
				],
			]
		];
		yield [
			false,
			[ 'user' ],
			[
				'tables' => [ 'revision', 'user' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					[
						'user_name',
					]
				),
				'joins' => [
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
				],
			]
		];
		yield [
			false,
			[ 'text' ],
			[
				'tables' => [ 'revision', 'text' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					[
						'old_text',
						'old_flags',
					]
				),
				'joins' => [
					'text' => [ 'INNER JOIN', [ 'rev_text_id=old_id' ] ],
				],
			]
		];
		yield [
			true,
			[ 'page', 'user', 'text' ],
			[
				'tables' => [ 'revision', 'page', 'user', 'text' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					$this->getContentHandlerQueryFields(),
					[
						'page_namespace',
						'page_title',
						'page_id',
						'page_latest',
						'page_is_redirect',
						'page_len',
						'user_name',
						'old_text',
						'old_flags',
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
					'text' => [ 'INNER JOIN', [ 'rev_text_id=old_id' ] ],
				],
			]
		];
	}

	/**
	 * @dataProvider provideGetQueryInfo
	 * @covers \MediaWiki\Storage\RevisionStore::getQueryInfo
	 */
	public function testGetQueryInfo( $contentHandlerUseDb, $options, $expected ) {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideMwServices();
		$store = $this->getRevisionStore();
		$store->setContentHandlerUseDB( $contentHandlerUseDb );
		$this->assertEquals( $expected, $store->getQueryInfo( $options ) );
	}

	private function getDefaultArchiveFields() {
		return [
			'ar_id',
			'ar_page_id',
			'ar_namespace',
			'ar_title',
			'ar_rev_id',
			'ar_text_id',
			'ar_timestamp',
			'ar_minor_edit',
			'ar_deleted',
			'ar_len',
			'ar_parent_id',
			'ar_sha1',
		];
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getArchiveQueryInfo
	 */
	public function testGetArchiveQueryInfo_contentHandlerDb() {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideMwServices();
		$store = $this->getRevisionStore();
		$store->setContentHandlerUseDB( true );
		$this->assertEquals(
			[
				'tables' => [
					'archive'
				],
				'fields' => array_merge(
					$this->getDefaultArchiveFields(),
					[
						'ar_comment_text' => 'ar_comment',
						'ar_comment_data' => 'NULL',
						'ar_comment_cid' => 'NULL',
						'ar_user_text' => 'ar_user_text',
						'ar_user' => 'ar_user',
						'ar_actor' => 'NULL',
						'ar_content_format',
						'ar_content_model',
					]
				),
				'joins' => [],
			],
			$store->getArchiveQueryInfo()
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getArchiveQueryInfo
	 */
	public function testGetArchiveQueryInfo_noContentHandlerDb() {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideMwServices();
		$store = $this->getRevisionStore();
		$store->setContentHandlerUseDB( false );
		$this->assertEquals(
			[
				'tables' => [
					'archive'
				],
				'fields' => array_merge(
					$this->getDefaultArchiveFields(),
					[
						'ar_comment_text' => 'ar_comment',
						'ar_comment_data' => 'NULL',
						'ar_comment_cid' => 'NULL',
						'ar_user_text' => 'ar_user_text',
						'ar_user' => 'ar_user',
						'ar_actor' => 'NULL',
					]
				),
				'joins' => [],
			],
			$store->getArchiveQueryInfo()
		);
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

		$blobStore = new SqlBlobStore( wfGetLB(), $cache );
		$blobStore->setLegacyEncoding( $encoding, Language::factory( $locale ) );

		$store = $this->getRevisionStore( wfGetLB(), $blobStore, $cache );

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

		$blobStore = new SqlBlobStore( wfGetLB(), $cache );
		$blobStore->setLegacyEncoding( 'windows-1252', Language::factory( 'en' ) );

		$store = $this->getRevisionStore( wfGetLB(), $blobStore, $cache );

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

}
