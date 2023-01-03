<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\IncompleteRevisionException;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use MWException;
use MWTimestamp;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use WikitextContent;
use WikitextContentHandler;

/**
 * Tests RevisionStore
 */
class RevisionStoreTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return RevisionStore
	 */
	private function getRevisionStore() {
		return $this->getServiceContainer()->getRevisionStore();
	}

	/**
	 * @param IDatabase $db
	 *
	 * @return MockObject|ILoadBalancer
	 */
	private function installMockLoadBalancer( IDatabase $db ) {
		$lb = $this->createNoOpMock(
			ILoadBalancer::class,
			[ 'getConnectionRef', 'getLocalDomainID', 'reuseConnection' ]
		);

		$lb->method( 'getConnectionRef' )->willReturn( $db );
		$lb->method( 'getLocalDomainID' )->willReturn( 'fake' );

		$lbf = $this->createNoOpMock( LBFactory::class, [ 'getMainLB', 'getLocalDomainID' ] );
		$lbf->method( 'getMainLB' )->willReturn( $lb );
		$lbf->method( 'getLocalDomainID' )->willReturn( 'fake' );

		$this->setService( 'DBLoadBalancerFactory', $lbf );
		return $lb;
	}

	/**
	 * @return MockObject|IDatabase
	 */
	private function installMockDatabase() {
		$db = $this->getMockBuilder( IDatabase::class )
			->disableAutoReturnValueGeneration()
			->disableOriginalConstructor()->getMock();

		$db->method( 'getDomainId' )->willReturn( 'fake' );

		$this->installMockLoadBalancer( $db );
		return $db;
	}

	private function getDummyPageRow( $extra = [] ) {
		return (object)( $extra + [
			'page_id' => 1337,
			'page_namespace' => 0,
			'page_title' => 'Test',
			'page_is_redirect' => 0,
			'page_is_new' => 0,
			'page_touched' => MWTimestamp::now(),
			'page_links_updated' => MWTimestamp::now(),
			'page_latest' => 23948576,
			'page_len' => 2323,
			'page_content_model' => CONTENT_MODEL_WIKITEXT,
		] );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromPageId() {
		$db = $this->installMockDatabase();

		// First query is by page ID. Return result
		$db
			->method( 'selectRow' )
			->with(
				[ 'page' ],
				$this->anything(),
				[ 'page_id' => 1 ]
			)
			->willReturn( $this->getDummyPageRow( [
				'page_id' => '1',
				'page_namespace' => '3',
				'page_title' => 'Food',
			] ) );

		$store = $this->getRevisionStore();
		$title = $store->getTitle( 1, 2, RevisionStore::READ_NORMAL );

		$this->assertSame( 3, $title->getNamespace() );
		$this->assertSame( 'Food', $title->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromPageIdOnFallback() {
		$db = $this->installMockDatabase();

		$db
			->method( 'selectRow' )
			->withConsecutive(
				[
					[ 'page' ],
					$this->anything(),
					[ 'page_id' => 1 ]
				],
				[
					[ 0 => 'page', 'revision' => 'revision' ],
					$this->anything(),
					[ 'rev_id' => 2 ]
				],
				[
					[ 'page' ],
					$this->anything(),
					[ 'page_id' => 1 ]
				]
			)
			->willReturnOnConsecutiveCalls(
				// First query, by page_id, no result
				false,
				// Second query, by rev_id, no result
				false,
				// Third query, retrying by page_id again on master
				$this->getDummyPageRow( [
					'page_namespace' => '2',
					'page_title' => 'Foodey',
				] )
			);

		$store = $this->getRevisionStore();
		$title = $store->getTitle( 1, 2, RevisionStore::READ_NORMAL );

		$this->assertSame( 2, $title->getNamespace() );
		$this->assertSame( 'Foodey', $title->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromRevId() {
		$db = $this->installMockDatabase();

		// First call to Title::newFromID, faking no result (db lag?)
		// Second select using rev_id, faking no result (db lag?)
		$db
			->method( 'selectRow' )
			->withConsecutive(
				[
					[ 'page' ],
					$this->anything(),
					[ 'page_id' => 1 ]
				],
				[
					[ 0 => 'page', 'revision' => 'revision' ],
					$this->anything(),
					[ 'rev_id' => 2 ]
				]
			)
			->willReturnOnConsecutiveCalls(
				false,
				$this->getDummyPageRow( [
					'page_namespace' => '1',
					'page_title' => 'Food2',
				] )
			);

		$store = $this->getRevisionStore();
		$title = $store->getTitle( 1, 2, RevisionStore::READ_NORMAL );

		$this->assertSame( 1, $title->getNamespace() );
		$this->assertSame( 'Food2', $title->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromRevIdOnFallback() {
		$db = $this->installMockDatabase();

		$db
			->method( 'selectRow' )
			->withConsecutive(
				[
					[ 'page' ],
					$this->anything(),
					[ 'page_id' => 1 ]
				],
				[
					[ 0 => 'page', 'revision' => 'revision' ],
					$this->anything(),
					[ 'rev_id' => 2 ]
				],
				[
					[ 'page' ],
					$this->anything(),
					[ 'page_id' => 1 ]
				],
				[
					[ 0 => 'page', 'revision' => 'revision' ],
					$this->anything(),
					[ 'rev_id' => 2 ]
				]
			)
			->willReturnOnConsecutiveCalls(
				// First query, by page_id, no result
				false,
				// Second query, by rev_id, no result
				false,
				// Third query, retrying by page_id again on master, still no result
				false,
				// Fourth query, by rev_id again
				$this->getDummyPageRow( [
					'page_namespace' => '2',
					'page_title' => 'Foodey',
				] )
			);

		$store = $this->getRevisionStore();
		$title = $store->getTitle( 1, 2, RevisionStore::READ_NORMAL );

		$this->assertSame( 2, $title->getNamespace() );
		$this->assertSame( 'Foodey', $title->getDBkey() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_correctFallbackAndthrowsExceptionAfterFallbacks() {
		$db = $this->createMock( IDatabase::class );
		$mockLoadBalancer = $this->installMockLoadBalancer( $db );

		// Assert that the first call uses a REPLICA and the second falls back to master

		// RevisionStore getTitle uses getConnectionRef
		$mockLoadBalancer->expects( $this->exactly( 4 ) )
			->method( 'getConnectionRef' )
			->willReturnCallback( function ( $masterOrReplica ) use ( $db ) {
				static $callCounter = 0;
				$callCounter++;
				// The first call should be to a REPLICA, and the second a MASTER.
				if ( $callCounter < 3 ) {
					$this->assertSame( DB_REPLICA, $masterOrReplica );
				} else {
					$this->assertSame( DB_PRIMARY, $masterOrReplica );
				}
				return $db;
			} );

		// First and third call to Title::newFromID, faking no result
		$db
			->method( 'selectRow' )
			->withConsecutive(
				[
					[ 'page' ],
					$this->anything(),
					[ 'page_id' => 1 ]
				],
				[
					[ 0 => 'page', 'revision' => 'revision' ],
					$this->anything(),
					[ 'rev_id' => 2 ]
				],
				[
					[ 'page' ],
					$this->anything(),
					[ 'page_id' => 1 ]
				],
				[
					[ 0 => 'page', 'revision' => 'revision' ],
					$this->anything(),
					[ 'rev_id' => 2 ]
				]
			)
			->willReturn( false );

		$store = $this->getRevisionStore( $mockLoadBalancer );

		$this->expectException( RevisionAccessException::class );
		$store->getTitle( 1, 2, RevisionStore::READ_NORMAL );
	}

	public function provideIsRevisionRow() {
		yield 'invalid row type' => [
			'row' => new class() {
			},
			'expect' => false,
		];
		yield 'invalid row' => [
			'row' => (object)[ 'blabla' => 'bla' ],
			'expect' => false,
		];
		yield 'valid row' => [
			'row' => (object)[
				'rev_id' => 321,
				'rev_page' => 123,
				'rev_timestamp' => ConvertibleTimestamp::now(),
				'rev_minor_edit' => 0,
				'rev_deleted' => 0,
				'rev_len' => 10,
				'rev_parent_id' => 123,
				'rev_sha1' => 'abc',
				'rev_comment_text' => 'blabla',
				'rev_comment_data' => 'blablabla',
				'rev_comment_cid' => 1,
				'rev_actor' => 1,
				'rev_user' => 1,
				'rev_user_text' => 'alala',
			],
			'expect' => true,
		];
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::isRevisionRow
	 * @dataProvider provideIsRevisionRow
	 */
	public function testIsRevisionRow( $row, bool $expect ) {
		$this->assertSame( $expect, $this->getRevisionStore()->isRevisionRow( $row ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::failOnNull
	 */
	public function testFailOnNull() {
		$revStore = TestingAccessWrapper::newFromObject( $this->getRevisionStore() );
		// Success - not null
		$this->assertSame( 123, $revStore->failOnNull( 123, 'value' ) );

		// Failure - null throws exception
		$this->expectException( IncompleteRevisionException::class );
		$revStore->failOnNull( null, 'value' );
	}

	public function provideFailOnEmpty() {
		yield 'null' => [ null ];
		yield 'zero' => [ 0 ];
		yield 'empty string' => [ '' ];
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::failOnEmpty
	 * @dataProvider provideFailOnEmpty
	 */
	public function testFailOnEmpty( $emptyValue ) {
		$revStore = TestingAccessWrapper::newFromObject( $this->getRevisionStore() );
		$this->expectException( IncompleteRevisionException::class );
		$revStore->failOnEmpty( $emptyValue, 'value' );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::failOnEmpty
	 */
	public function testFailOnEmpty_pass() {
		$revStore = TestingAccessWrapper::newFromObject( $this->getRevisionStore() );
		$this->assertSame( 123, $revStore->failOnEmpty( 123, 'value' ) );
	}

	public function provideCheckContent() {
		yield 'unsupported format' => [
			false,
			false,
			'Can\'t use format text/x-wiki with content model wikitext on [0:Example] role main'
		];
		yield 'invalid content' => [
			true,
			false,
			'New content for [0:Example] role main is not valid! Content model is wikitext'
		];
		yield 'valid content' => [ true, true, null ];
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::checkContent
	 * @dataProvider provideCheckContent
	 */
	public function testCheckContent( bool $isSupported, bool $isValid, ?string $error ) {
		$revStore = TestingAccessWrapper::newFromObject( $this->getRevisionStore() );
		$contentHandler = $this->createMock( WikitextContentHandler::class );
		$contentHandler->method( 'isSupportedFormat' )->willReturn( $isSupported );
		$content = $this->createMock( WikitextContent::class );
		$content->method( 'getModel' )->willReturn( CONTENT_MODEL_WIKITEXT );
		$content->method( 'getDefaultFormat' )->willReturn( CONTENT_FORMAT_WIKITEXT );
		$content->method( 'getContentHandler' )->willReturn( $contentHandler );
		$content->method( 'isValid' )->willReturn( $isValid );

		if ( $error !== null ) {
			$this->expectException( MWException::class );
			$this->expectExceptionMessage( $error );
		}
		$revStore->checkContent(
			$content,
			new PageIdentityValue( 0, NS_MAIN, 'Example', PageIdentityValue::LOCAL ),
			SlotRecord::MAIN
		);
		// Avoid issues with no assertions for the non-exception case
		$this->addToAssertionCount( 1 );
	}
}
