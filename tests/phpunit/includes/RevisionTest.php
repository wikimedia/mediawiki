<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Test cases in RevisionTest should not interact with the Database.
 * For test cases that need Database interaction see RevisionDbTestBase.
 */
class RevisionTest extends MediaWikiTestCase {

	public function provideConstructFromArray() {
		yield 'with text' => [
			[
				'text' => 'hello world.',
				'content_model' => CONTENT_MODEL_JAVASCRIPT
			],
		];
		yield 'with content' => [
			[
				'content' => new JavaScriptContent( 'hellow world.' )
			],
		];
		// FIXME: test with and without user ID, and with a user object.
		// We can't prepare that here though, since we don't yet have a dummy DB
	}

	/**
	 * @param string $model
	 * @return Title
	 */
	public function getMockTitle( $model = CONTENT_MODEL_WIKITEXT ) {
		$mock = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'getNamespace' )
			->will( $this->returnValue( $this->getDefaultWikitextNS() ) );
		$mock->expects( $this->any() )
			->method( 'getPrefixedText' )
			->will( $this->returnValue( 'RevisionTest' ) );
		$mock->expects( $this->any() )
			->method( 'getDBkey' )
			->will( $this->returnValue( 'RevisionTest' ) );
		$mock->expects( $this->any() )
			->method( 'getArticleID' )
			->will( $this->returnValue( 23 ) );
		$mock->expects( $this->any() )
			->method( 'getContentModel' )
			->will( $this->returnValue( $model ) );

		return $mock;
	}

	/**
	 * @dataProvider provideConstructFromArray
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromArray( $rowArray ) {
		$rev = new Revision( $rowArray, 0, $this->getMockTitle() );
		$this->assertNotNull( $rev->getContent(), 'no content object available' );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContent()->getModel() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContentModel() );
	}

	/**
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromEmptyArray() {
		$rev = new Revision( [], 0, $this->getMockTitle() );
		$this->assertNull( $rev->getContent(), 'no content object should be available' );
	}

	/**
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromArrayWithBadPageId() {
		Wikimedia\suppressWarnings();
		$rev = new Revision( [ 'page' => 77777777 ] );
		$this->assertSame( 77777777, $rev->getPage() );
		Wikimedia\restoreWarnings();
	}

	public function provideConstructFromArray_userSetAsExpected() {
		yield 'no user defaults to wgUser' => [
			[
				'content' => new JavaScriptContent( 'hello world.' ),
			],
			null,
			null,
		];
		yield 'user text and id' => [
			[
				'content' => new JavaScriptContent( 'hello world.' ),
				'user_text' => 'SomeTextUserName',
				'user' => 99,

			],
			99,
			'SomeTextUserName',
		];
		yield 'user text only' => [
			[
				'content' => new JavaScriptContent( 'hello world.' ),
				'user_text' => '111.111.111.111',
			],
			0,
			'111.111.111.111',
		];
	}

	/**
	 * @dataProvider provideConstructFromArray_userSetAsExpected
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 *
	 * @param array $rowArray
	 * @param mixed $expectedUserId null to expect the current wgUser ID
	 * @param mixed $expectedUserName null to expect the current wgUser name
	 */
	public function testConstructFromArray_userSetAsExpected(
		array $rowArray,
		$expectedUserId,
		$expectedUserName
	) {
		$testUser = $this->getTestUser()->getUser();
		$this->setMwGlobals( 'wgUser', $testUser );
		if ( $expectedUserId === null ) {
			$expectedUserId = $testUser->getId();
		}
		if ( $expectedUserName === null ) {
			$expectedUserName = $testUser->getName();
		}

		$rev = new Revision( $rowArray, 0, $this->getMockTitle() );
		$this->assertEquals( $expectedUserId, $rev->getUser() );
		$this->assertEquals( $expectedUserName, $rev->getUserText() );
	}

	public function provideConstructFromArrayThrowsExceptions() {
		yield 'content and text_id both not empty' => [
			[
				'content' => new WikitextContent( 'GOAT' ),
				'text_id' => 'someid',
			],
			new MWException( "Text already stored in external store (id someid), " .
				"can't serialize content object" )
		];
		yield 'with bad content object (class)' => [
			[ 'content' => new stdClass() ],
			new MWException( 'content field must contain a Content object.' )
		];
		yield 'with bad content object (string)' => [
			[ 'content' => 'ImAGoat' ],
			new MWException( 'content field must contain a Content object.' )
		];
		yield 'bad row format' => [
			'imastring, not a row',
			new InvalidArgumentException(
				'$row must be a row object, an associative array, or a RevisionRecord'
			)
		];
	}

	/**
	 * @dataProvider provideConstructFromArrayThrowsExceptions
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromArrayThrowsExceptions( $rowArray, Exception $expectedException ) {
		$this->setExpectedException(
			get_class( $expectedException ),
			$expectedException->getMessage(),
			$expectedException->getCode()
		);
		new Revision( $rowArray, 0, $this->getMockTitle() );
	}

	/**
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromNothing() {
		$this->setExpectedException(
			InvalidArgumentException::class
		);
		new Revision( [] );
	}

	public function provideConstructFromRow() {
		yield 'Full construction' => [
			[
				'rev_id' => '42',
				'rev_page' => '23',
				'rev_text_id' => '2',
				'rev_timestamp' => '20171017114835',
				'rev_user_text' => '127.0.0.1',
				'rev_user' => '0',
				'rev_minor_edit' => '0',
				'rev_deleted' => '0',
				'rev_len' => '46',
				'rev_parent_id' => '1',
				'rev_sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'rev_comment_text' => 'Goat Comment!',
				'rev_comment_data' => null,
				'rev_comment_cid' => null,
				'rev_content_format' => 'GOATFORMAT',
				'rev_content_model' => 'GOATMODEL',
			],
			function ( RevisionTest $testCase, Revision $rev ) {
				$testCase->assertSame( 42, $rev->getId() );
				$testCase->assertSame( 23, $rev->getPage() );
				$testCase->assertSame( 2, $rev->getTextId() );
				$testCase->assertSame( '20171017114835', $rev->getTimestamp() );
				$testCase->assertSame( '127.0.0.1', $rev->getUserText() );
				$testCase->assertSame( 0, $rev->getUser() );
				$testCase->assertSame( false, $rev->isMinor() );
				$testCase->assertSame( false, $rev->isDeleted( Revision::DELETED_TEXT ) );
				$testCase->assertSame( 46, $rev->getSize() );
				$testCase->assertSame( 1, $rev->getParentId() );
				$testCase->assertSame( 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z', $rev->getSha1() );
				$testCase->assertSame( 'Goat Comment!', $rev->getComment() );
				$testCase->assertSame( 'GOATFORMAT', $rev->getContentFormat() );
				$testCase->assertSame( 'GOATMODEL', $rev->getContentModel() );
			}
		];
		yield 'default field values' => [
			[
				'rev_id' => '42',
				'rev_page' => '23',
				'rev_text_id' => '2',
				'rev_timestamp' => '20171017114835',
				'rev_user_text' => '127.0.0.1',
				'rev_user' => '0',
				'rev_minor_edit' => '0',
				'rev_deleted' => '0',
				'rev_comment_text' => 'Goat Comment!',
				'rev_comment_data' => null,
				'rev_comment_cid' => null,
			],
			function ( RevisionTest $testCase, Revision $rev ) {
				// parent ID may be null
				$testCase->assertSame( null, $rev->getParentId(), 'revision id' );

				// given fields
				$testCase->assertSame( $rev->getTimestamp(), '20171017114835', 'timestamp' );
				$testCase->assertSame( $rev->getUserText(), '127.0.0.1', 'user name' );
				$testCase->assertSame( $rev->getUser(), 0, 'user id' );
				$testCase->assertSame( $rev->getComment(), 'Goat Comment!' );
				$testCase->assertSame( false, $rev->isMinor(), 'minor edit' );
				$testCase->assertSame( 0, $rev->getVisibility(), 'visibility flags' );

				// computed fields
				$testCase->assertNotNull( $rev->getSize(), 'size' );
				$testCase->assertNotNull( $rev->getSha1(), 'hash' );

				// NOTE: model and format will be detected based on the namespace of the (mock) title
				$testCase->assertSame( 'text/x-wiki', $rev->getContentFormat(), 'format' );
				$testCase->assertSame( 'wikitext', $rev->getContentModel(), 'model' );
			}
		];
	}

	/**
	 * @dataProvider provideConstructFromRow
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromRow( array $arrayData, $assertions ) {
		$data = 'Hello goat.'; // needs to match model and format

		$blobStore = $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()
			->getMock();

		$blobStore->method( 'getBlob' )
			->will( $this->returnValue( $data ) );

		$blobStore->method( 'getTextIdFromAddress' )
			->will( $this->returnCallback(
				function ( $address ) {
					// Turn "tt:1234" into 12345.
					// Note that this must be functional so we can test getTextId().
					// Ideally, we'd un-mock getTextIdFromAddress and use its actual implementation.
					$parts = explode( ':', $address );
					return (int)array_pop( $parts );
				}
			) );

		// Note override internal service, so RevisionStore uses it as well.
		$this->setService( 'BlobStoreFactory', $this->mockBlobStoreFactory( $blobStore ) );

		$row = (object)$arrayData;
		$rev = new Revision( $row, 0, $this->getMockTitle() );
		$assertions( $this, $rev );
	}

	/**
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromRowWithBadPageId() {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideMwServices();
		Wikimedia\suppressWarnings();
		$rev = new Revision( (object)[ 'rev_page' => 77777777 ] );
		$this->assertSame( 77777777, $rev->getPage() );
		Wikimedia\restoreWarnings();
	}

	public function provideGetRevisionText() {
		yield 'Generic test' => [
			'This is a goat of revision text.',
			[
				'old_flags' => '',
				'old_text' => 'This is a goat of revision text.',
			],
		];
	}

	public function provideGetId() {
		yield [
			[],
			null
		];
		yield [
			[ 'id' => 998 ],
			998
		];
	}

	/**
	 * @dataProvider provideGetId
	 * @covers Revision::getId
	 */
	public function testGetId( $rowArray, $expectedId ) {
		$rev = new Revision( $rowArray, 0, $this->getMockTitle() );
		$this->assertEquals( $expectedId, $rev->getId() );
	}

	public function provideSetId() {
		yield [ '123', 123 ];
		yield [ 456, 456 ];
	}

	/**
	 * @dataProvider provideSetId
	 * @covers Revision::setId
	 */
	public function testSetId( $input, $expected ) {
		$rev = new Revision( [], 0, $this->getMockTitle() );
		$rev->setId( $input );
		$this->assertSame( $expected, $rev->getId() );
	}

	public function provideSetUserIdAndName() {
		yield [ '123', 123, 'GOaT' ];
		yield [ 456, 456, 'GOaT' ];
	}

	/**
	 * @dataProvider provideSetUserIdAndName
	 * @covers Revision::setUserIdAndName
	 */
	public function testSetUserIdAndName( $inputId, $expectedId, $name ) {
		$rev = new Revision( [], 0, $this->getMockTitle() );
		$rev->setUserIdAndName( $inputId, $name );
		$this->assertSame( $expectedId, $rev->getUser( Revision::RAW ) );
		$this->assertEquals( $name, $rev->getUserText( Revision::RAW ) );
	}

	public function provideGetTextId() {
		yield [ [], null ];
		yield [ [ 'text_id' => '123' ], 123 ];
		yield [ [ 'text_id' => 456 ], 456 ];
	}

	/**
	 * @dataProvider provideGetTextId
	 * @covers Revision::getTextId()
	 */
	public function testGetTextId( $rowArray, $expected ) {
		$rev = new Revision( $rowArray, 0, $this->getMockTitle() );
		$this->assertSame( $expected, $rev->getTextId() );
	}

	public function provideGetParentId() {
		yield [ [], null ];
		yield [ [ 'parent_id' => '123' ], 123 ];
		yield [ [ 'parent_id' => 456 ], 456 ];
	}

	/**
	 * @dataProvider provideGetParentId
	 * @covers Revision::getParentId()
	 */
	public function testGetParentId( $rowArray, $expected ) {
		$rev = new Revision( $rowArray, 0, $this->getMockTitle() );
		$this->assertSame( $expected, $rev->getParentId() );
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionText
	 */
	public function testGetRevisionText( $expected, $rowData, $prefix = 'old_', $wiki = false ) {
		$this->assertEquals(
			$expected,
			Revision::getRevisionText( (object)$rowData, $prefix, $wiki ) );
	}

	public function provideGetRevisionTextWithZlibExtension() {
		yield 'Generic gzip test' => [
			'This is a small goat of revision text.',
			[
				'old_flags' => 'gzip',
				'old_text' => gzdeflate( 'This is a small goat of revision text.' ),
			],
		];
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionTextWithZlibExtension
	 */
	public function testGetRevisionWithZlibExtension( $expected, $rowData ) {
		$this->checkPHPExtension( 'zlib' );
		$this->testGetRevisionText( $expected, $rowData );
	}

	private function getWANObjectCache() {
		return new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
	}

	/**
	 * @return SqlBlobStore
	 */
	private function getBlobStore() {
		/** @var LoadBalancer $lb */
		$lb = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();

		$cache = $this->getWANObjectCache();

		$blobStore = new SqlBlobStore( $lb, $cache );
		return $blobStore;
	}

	private function mockBlobStoreFactory( $blobStore ) {
		/** @var LoadBalancer $lb */
		$factory = $this->getMockBuilder( BlobStoreFactory::class )
			->disableOriginalConstructor()
			->getMock();
		$factory->expects( $this->any() )
			->method( 'newBlobStore' )
			->willReturn( $blobStore );
		$factory->expects( $this->any() )
			->method( 'newSqlBlobStore' )
			->willReturn( $blobStore );
		return $factory;
	}

	/**
	 * @return RevisionStore
	 */
	private function getRevisionStore() {
		/** @var LoadBalancer $lb */
		$lb = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();

		$cache = $this->getWANObjectCache();

		$blobStore = new RevisionStore(
			$lb,
			$this->getBlobStore(),
			$cache,
			MediaWikiServices::getInstance()->getCommentStore(),
			MediaWikiServices::getInstance()->getActorMigration()
		);
		return $blobStore;
	}

	public function provideGetRevisionTextWithLegacyEncoding() {
		yield 'Utf8Native' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'fr',
			'iso-8859-1',
			[
				'old_flags' => 'utf-8',
				'old_text' => "Wiki est l'\xc3\xa9cole superieur !",
			]
		];
		yield 'Utf8Legacy' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'fr',
			'iso-8859-1',
			[
				'old_flags' => '',
				'old_text' => "Wiki est l'\xe9cole superieur !",
			]
		];
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionTextWithLegacyEncoding
	 */
	public function testGetRevisionWithLegacyEncoding( $expected, $lang, $encoding, $rowData ) {
		$blobStore = $this->getBlobStore();
		$blobStore->setLegacyEncoding( $encoding, Language::factory( $lang ) );
		$this->setService( 'BlobStoreFactory', $this->mockBlobStoreFactory( $blobStore ) );

		$this->testGetRevisionText( $expected, $rowData );
	}

	public function provideGetRevisionTextWithGzipAndLegacyEncoding() {
		/**
		 * WARNING!
		 * Do not set the external flag!
		 * Otherwise, getRevisionText will hit the live database (if ExternalStore is enabled)!
		 */
		yield 'Utf8NativeGzip' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'fr',
			'iso-8859-1',
			[
				'old_flags' => 'gzip,utf-8',
				'old_text' => gzdeflate( "Wiki est l'\xc3\xa9cole superieur !" ),
			]
		];
		yield 'Utf8LegacyGzip' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'fr',
			'iso-8859-1',
			[
				'old_flags' => 'gzip',
				'old_text' => gzdeflate( "Wiki est l'\xe9cole superieur !" ),
			]
		];
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionTextWithGzipAndLegacyEncoding
	 */
	public function testGetRevisionWithGzipAndLegacyEncoding( $expected, $lang, $encoding, $rowData ) {
		$this->checkPHPExtension( 'zlib' );

		$blobStore = $this->getBlobStore();
		$blobStore->setLegacyEncoding( $encoding, Language::factory( $lang ) );
		$this->setService( 'BlobStoreFactory', $this->mockBlobStoreFactory( $blobStore ) );

		$this->testGetRevisionText( $expected, $rowData );
	}

	/**
	 * @covers Revision::compressRevisionText
	 */
	public function testCompressRevisionTextUtf8() {
		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertTrue( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should contain 'utf-8'" );
		$this->assertFalse( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should not contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			$row->old_text, "Direct check" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ), "getRevisionText" );
	}

	/**
	 * @covers Revision::compressRevisionText
	 */
	public function testCompressRevisionTextUtf8Gzip() {
		$this->checkPHPExtension( 'zlib' );

		$blobStore = $this->getBlobStore();
		$blobStore->setCompressBlobs( true );
		$this->setService( 'BlobStoreFactory', $this->mockBlobStoreFactory( $blobStore ) );

		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertTrue( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should contain 'utf-8'" );
		$this->assertTrue( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			gzinflate( $row->old_text ), "Direct check" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ), "getRevisionText" );
	}

	/**
	 * @covers Revision::loadFromTitle
	 */
	public function testLoadFromTitle() {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideMwServices();
		$title = $this->getMockTitle();

		$conditions = [
			'rev_id=page_latest',
			'page_namespace' => $title->getNamespace(),
			'page_title' => $title->getDBkey()
		];

		$row = (object)[
			'rev_id' => '42',
			'rev_page' => $title->getArticleID(),
			'rev_text_id' => '2',
			'rev_timestamp' => '20171017114835',
			'rev_user_text' => '127.0.0.1',
			'rev_user' => '0',
			'rev_minor_edit' => '0',
			'rev_deleted' => '0',
			'rev_len' => '46',
			'rev_parent_id' => '1',
			'rev_sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
			'rev_comment_text' => 'Goat Comment!',
			'rev_comment_data' => null,
			'rev_comment_cid' => null,
			'rev_content_format' => 'GOATFORMAT',
			'rev_content_model' => 'GOATMODEL',
		];

		$db = $this->getMock( IDatabase::class );
		$db->expects( $this->any() )
			->method( 'getDomainId' )
			->will( $this->returnValue( wfWikiID() ) );
		$db->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				$this->equalTo( [ 'revision', 'page', 'user' ] ),
				// We don't really care about the fields are they come from the selectField methods
				$this->isType( 'array' ),
				$this->equalTo( $conditions ),
				// Method name
				$this->stringContains( 'fetchRevisionRowFromConds' ),
				// We don't really care about the options here
				$this->isType( 'array' ),
				// We don't really care about the join conds are they come from the joinCond methods
				$this->isType( 'array' )
			)
			->willReturn( $row );

		$revision = Revision::loadFromTitle( $db, $title );

		$this->assertEquals( $title->getArticleID(), $revision->getTitle()->getArticleID() );
		$this->assertEquals( $row->rev_id, $revision->getId() );
		$this->assertEquals( $row->rev_len, $revision->getSize() );
		$this->assertEquals( $row->rev_sha1, $revision->getSha1() );
		$this->assertEquals( $row->rev_parent_id, $revision->getParentId() );
		$this->assertEquals( $row->rev_timestamp, $revision->getTimestamp() );
		$this->assertEquals( $row->rev_comment_text, $revision->getComment() );
		$this->assertEquals( $row->rev_user_text, $revision->getUserText() );
	}

	public function provideDecompressRevisionText() {
		yield '(no legacy encoding), false in false out' => [ false, false, [], false ];
		yield '(no legacy encoding), empty in empty out' => [ false, '', [], '' ];
		yield '(no legacy encoding), empty in empty out' => [ false, 'A', [], 'A' ];
		yield '(no legacy encoding), string in with gzip flag returns string' => [
			// gzip string below generated with gzdeflate( 'AAAABBAAA' )
			false, "sttttr\002\022\000", [ 'gzip' ], 'AAAABBAAA',
		];
		yield '(no legacy encoding), string in with object flag returns false' => [
			// gzip string below generated with serialize( 'JOJO' )
			false, "s:4:\"JOJO\";", [ 'object' ], false,
		];
		yield '(no legacy encoding), serialized object in with object flag returns string' => [
			false,
			// Using a TitleValue object as it has a getText method (which is needed)
			serialize( new TitleValue( 0, 'HHJJDDFF' ) ),
			[ 'object' ],
			'HHJJDDFF',
		];
		yield '(no legacy encoding), serialized object in with object & gzip flag returns string' => [
			false,
			// Using a TitleValue object as it has a getText method (which is needed)
			gzdeflate( serialize( new TitleValue( 0, '8219JJJ840' ) ) ),
			[ 'object', 'gzip' ],
			'8219JJJ840',
		];
		yield '(ISO-8859-1 encoding), string in string out' => [
			'ISO-8859-1',
			iconv( 'utf-8', 'ISO-8859-1', "1®Àþ1" ),
			[],
			'1®Àþ1',
		];
		yield '(ISO-8859-1 encoding), serialized object in with gzip flags returns string' => [
			'ISO-8859-1',
			gzdeflate( iconv( 'utf-8', 'ISO-8859-1', "4®Àþ4" ) ),
			[ 'gzip' ],
			'4®Àþ4',
		];
		yield '(ISO-8859-1 encoding), serialized object in with object flags returns string' => [
			'ISO-8859-1',
			serialize( new TitleValue( 0, iconv( 'utf-8', 'ISO-8859-1', "3®Àþ3" ) ) ),
			[ 'object' ],
			'3®Àþ3',
		];
		yield '(ISO-8859-1 encoding), serialized object in with object & gzip flags returns string' => [
			'ISO-8859-1',
			gzdeflate( serialize( new TitleValue( 0, iconv( 'utf-8', 'ISO-8859-1', "2®Àþ2" ) ) ) ),
			[ 'gzip', 'object' ],
			'2®Àþ2',
		];
	}

	/**
	 * @dataProvider provideDecompressRevisionText
	 * @covers Revision::decompressRevisionText
	 *
	 * @param bool $legacyEncoding
	 * @param mixed $text
	 * @param array $flags
	 * @param mixed $expected
	 */
	public function testDecompressRevisionText( $legacyEncoding, $text, $flags, $expected ) {
		$blobStore = $this->getBlobStore();
		if ( $legacyEncoding ) {
			$blobStore->setLegacyEncoding( $legacyEncoding, Language::factory( 'en' ) );
		}

		$this->setService( 'BlobStoreFactory', $this->mockBlobStoreFactory( $blobStore ) );
		$this->assertSame(
			$expected,
			Revision::decompressRevisionText( $text, $flags )
		);
	}

	/**
	 * @covers Revision::getRevisionText
	 */
	public function testGetRevisionText_returnsFalseWhenNoTextField() {
		$this->assertFalse( Revision::getRevisionText( new stdClass() ) );
	}

	public function provideTestGetRevisionText_returnsDecompressedTextFieldWhenNotExternal() {
		yield 'Just text' => [
			(object)[ 'old_text' => 'SomeText' ],
			'old_',
			'SomeText'
		];
		// gzip string below generated with gzdeflate( 'AAAABBAAA' )
		yield 'gzip text' => [
			(object)[
				'old_text' => "sttttr\002\022\000",
				'old_flags' => 'gzip'
			],
			'old_',
			'AAAABBAAA'
		];
		yield 'gzip text and different prefix' => [
			(object)[
				'jojo_text' => "sttttr\002\022\000",
				'jojo_flags' => 'gzip'
			],
			'jojo_',
			'AAAABBAAA'
		];
	}

	/**
	 * @dataProvider provideTestGetRevisionText_returnsDecompressedTextFieldWhenNotExternal
	 * @covers Revision::getRevisionText
	 */
	public function testGetRevisionText_returnsDecompressedTextFieldWhenNotExternal(
		$row,
		$prefix,
		$expected
	) {
		$this->assertSame( $expected, Revision::getRevisionText( $row, $prefix ) );
	}

	public function provideTestGetRevisionText_external_returnsFalseWhenNotEnoughUrlParts() {
		yield 'Just some text' => [ 'someNonUrlText' ];
		yield 'No second URL part' => [ 'someProtocol://' ];
	}

	/**
	 * @dataProvider provideTestGetRevisionText_external_returnsFalseWhenNotEnoughUrlParts
	 * @covers Revision::getRevisionText
	 */
	public function testGetRevisionText_external_returnsFalseWhenNotEnoughUrlParts(
		$text
	) {
		$this->assertFalse(
			Revision::getRevisionText(
				(object)[
					'old_text' => $text,
					'old_flags' => 'external',
				]
			)
		);
	}

	/**
	 * @covers Revision::getRevisionText
	 */
	public function testGetRevisionText_external_noOldId() {
		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ] )
		);
		$this->assertSame(
			'AAAABBAAA',
			Revision::getRevisionText(
				(object)[
					'old_text' => 'ForTesting://cluster1/12345',
					'old_flags' => 'external,gzip',
				]
			)
		);
	}

	/**
	 * @covers Revision::getRevisionText
	 */
	public function testGetRevisionText_external_oldId() {
		$cache = $this->getWANObjectCache();
		$this->setService( 'MainWANObjectCache', $cache );

		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ] )
		);

		$lb = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();

		$blobStore = new SqlBlobStore( $lb, $cache );
		$this->setService( 'BlobStoreFactory', $this->mockBlobStoreFactory( $blobStore ) );

		$this->assertSame(
			'AAAABBAAA',
			Revision::getRevisionText(
				(object)[
					'old_text' => 'ForTesting://cluster1/12345',
					'old_flags' => 'external,gzip',
					'old_id' => '7777',
				]
			)
		);

		$cacheKey = $cache->makeKey( 'revisiontext', 'textid', 'tt:7777' );
		$this->assertSame( 'AAAABBAAA', $cache->get( $cacheKey ) );
	}

	/**
	 * @covers Revision::userJoinCond
	 */
	public function testUserJoinCond() {
		$this->hideDeprecated( 'Revision::userJoinCond' );
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideMwServices();
		$this->assertEquals(
			[ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
			Revision::userJoinCond()
		);
	}

	/**
	 * @covers Revision::pageJoinCond
	 */
	public function testPageJoinCond() {
		$this->hideDeprecated( 'Revision::pageJoinCond' );
		$this->assertEquals(
			[ 'INNER JOIN', [ 'page_id = rev_page' ] ],
			Revision::pageJoinCond()
		);
	}

	private function overrideCommentStoreAndActorMigration() {
		$mockStore = $this->getMockBuilder( CommentStore::class )
			->disableOriginalConstructor()
			->getMock();
		$mockStore->expects( $this->any() )
			->method( 'getFields' )
			->willReturn( [ 'commentstore' => 'fields' ] );
		$mockStore->expects( $this->any() )
			->method( 'getJoin' )
			->willReturn( [
				'tables' => [ 'commentstore' => 'table' ],
				'fields' => [ 'commentstore' => 'field' ],
				'joins' => [ 'commentstore' => 'join' ],
			] );
		$this->setService( 'CommentStore', $mockStore );

		$mockStore = $this->getMockBuilder( ActorMigration::class )
			->disableOriginalConstructor()
			->getMock();
		$mockStore->expects( $this->any() )
			->method( 'getJoin' )
			->willReturnCallback( function ( $key ) {
				$p = strtok( $key, '_' );
				return [
					'tables' => [ 'actormigration' => 'table' ],
					'fields' => [
						$p . '_user' => 'actormigration_user',
						$p . '_user_text' => 'actormigration_user_text',
						$p . '_actor' => 'actormigration_actor',
					],
					'joins' => [ 'actormigration' => 'join' ],
				];
			} );
		$this->setService( 'ActorMigration', $mockStore );
	}

	public function provideSelectFields() {
		yield [
			true,
			[
				'rev_id',
				'rev_page',
				'rev_text_id',
				'rev_timestamp',
				'rev_user_text',
				'rev_user',
				'rev_actor' => 'NULL',
				'rev_minor_edit',
				'rev_deleted',
				'rev_len',
				'rev_parent_id',
				'rev_sha1',
				'commentstore' => 'fields',
				'rev_content_format',
				'rev_content_model',
			]
		];
		yield [
			false,
			[
				'rev_id',
				'rev_page',
				'rev_text_id',
				'rev_timestamp',
				'rev_user_text',
				'rev_user',
				'rev_actor' => 'NULL',
				'rev_minor_edit',
				'rev_deleted',
				'rev_len',
				'rev_parent_id',
				'rev_sha1',
				'commentstore' => 'fields',
			]
		];
	}

	/**
	 * @dataProvider provideSelectFields
	 * @covers Revision::selectFields
	 */
	public function testSelectFields( $contentHandlerUseDB, $expected ) {
		$this->hideDeprecated( 'Revision::selectFields' );
		$this->setMwGlobals( 'wgContentHandlerUseDB', $contentHandlerUseDB );
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideCommentStoreAndActorMigration();
		$this->assertEquals( $expected, Revision::selectFields() );
	}

	public function provideSelectArchiveFields() {
		yield [
			true,
			[
				'ar_id',
				'ar_page_id',
				'ar_rev_id',
				'ar_text_id',
				'ar_timestamp',
				'ar_user_text',
				'ar_user',
				'ar_actor' => 'NULL',
				'ar_minor_edit',
				'ar_deleted',
				'ar_len',
				'ar_parent_id',
				'ar_sha1',
				'commentstore' => 'fields',
				'ar_content_format',
				'ar_content_model',
			]
		];
		yield [
			false,
			[
				'ar_id',
				'ar_page_id',
				'ar_rev_id',
				'ar_text_id',
				'ar_timestamp',
				'ar_user_text',
				'ar_user',
				'ar_actor' => 'NULL',
				'ar_minor_edit',
				'ar_deleted',
				'ar_len',
				'ar_parent_id',
				'ar_sha1',
				'commentstore' => 'fields',
			]
		];
	}

	/**
	 * @dataProvider provideSelectArchiveFields
	 * @covers Revision::selectArchiveFields
	 */
	public function testSelectArchiveFields( $contentHandlerUseDB, $expected ) {
		$this->hideDeprecated( 'Revision::selectArchiveFields' );
		$this->setMwGlobals( 'wgContentHandlerUseDB', $contentHandlerUseDB );
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideCommentStoreAndActorMigration();
		$this->assertEquals( $expected, Revision::selectArchiveFields() );
	}

	/**
	 * @covers Revision::selectTextFields
	 */
	public function testSelectTextFields() {
		$this->hideDeprecated( 'Revision::selectTextFields' );
		$this->assertEquals(
			[
				'old_text',
				'old_flags',
			],
			Revision::selectTextFields()
		);
	}

	/**
	 * @covers Revision::selectPageFields
	 */
	public function testSelectPageFields() {
		$this->hideDeprecated( 'Revision::selectPageFields' );
		$this->assertEquals(
			[
				'page_namespace',
				'page_title',
				'page_id',
				'page_latest',
				'page_is_redirect',
				'page_len',
			],
			Revision::selectPageFields()
		);
	}

	/**
	 * @covers Revision::selectUserFields
	 */
	public function testSelectUserFields() {
		$this->hideDeprecated( 'Revision::selectUserFields' );
		$this->assertEquals(
			[
				'user_name',
			],
			Revision::selectUserFields()
		);
	}

	public function provideGetArchiveQueryInfo() {
		yield 'wgContentHandlerUseDB false' => [
			[
				'wgContentHandlerUseDB' => false,
			],
			[
				'tables' => [
					'archive',
					'commentstore' => 'table',
					'actormigration' => 'table',
				],
				'fields' => [
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
					'commentstore' => 'field',
					'ar_user' => 'actormigration_user',
					'ar_user_text' => 'actormigration_user_text',
					'ar_actor' => 'actormigration_actor',
				],
				'joins' => [ 'commentstore' => 'join', 'actormigration' => 'join' ],
			]
		];
		yield 'wgContentHandlerUseDB true' => [
			[
				'wgContentHandlerUseDB' => true,
			],
			[
				'tables' => [
					'archive',
					'commentstore' => 'table',
					'actormigration' => 'table',
				],
				'fields' => [
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
					'commentstore' => 'field',
					'ar_user' => 'actormigration_user',
					'ar_user_text' => 'actormigration_user_text',
					'ar_actor' => 'actormigration_actor',
					'ar_content_format',
					'ar_content_model',
				],
				'joins' => [ 'commentstore' => 'join', 'actormigration' => 'join' ],
			]
		];
	}

	/**
	 * @covers Revision::getArchiveQueryInfo
	 * @dataProvider provideGetArchiveQueryInfo
	 */
	public function testGetArchiveQueryInfo( $globals, $expected ) {
		$this->setMwGlobals( $globals );
		$this->overrideCommentStoreAndActorMigration();

		$revisionStore = $this->getRevisionStore();
		$revisionStore->setContentHandlerUseDB( $globals['wgContentHandlerUseDB'] );
		$this->setService( 'RevisionStore', $revisionStore );
		$this->assertEquals(
			$expected,
			Revision::getArchiveQueryInfo()
		);
	}

	public function provideGetQueryInfo() {
		yield 'wgContentHandlerUseDB false, opts none' => [
			[
				'wgContentHandlerUseDB' => false,
			],
			[],
			[
				'tables' => [ 'revision', 'commentstore' => 'table', 'actormigration' => 'table' ],
				'fields' => [
					'rev_id',
					'rev_page',
					'rev_text_id',
					'rev_timestamp',
					'rev_minor_edit',
					'rev_deleted',
					'rev_len',
					'rev_parent_id',
					'rev_sha1',
					'commentstore' => 'field',
					'rev_user' => 'actormigration_user',
					'rev_user_text' => 'actormigration_user_text',
					'rev_actor' => 'actormigration_actor',
				],
				'joins' => [ 'commentstore' => 'join', 'actormigration' => 'join' ],
			],
		];
		yield 'wgContentHandlerUseDB false, opts page' => [
			[
				'wgContentHandlerUseDB' => false,
			],
			[ 'page' ],
			[
				'tables' => [ 'revision', 'commentstore' => 'table', 'actormigration' => 'table', 'page' ],
				'fields' => [
					'rev_id',
					'rev_page',
					'rev_text_id',
					'rev_timestamp',
					'rev_minor_edit',
					'rev_deleted',
					'rev_len',
					'rev_parent_id',
					'rev_sha1',
					'commentstore' => 'field',
					'rev_user' => 'actormigration_user',
					'rev_user_text' => 'actormigration_user_text',
					'rev_actor' => 'actormigration_actor',
					'page_namespace',
					'page_title',
					'page_id',
					'page_latest',
					'page_is_redirect',
					'page_len',
				],
				'joins' => [
					'page' => [
						'INNER JOIN',
						[ 'page_id = rev_page' ],
					],
					'commentstore' => 'join',
					'actormigration' => 'join',
				],
			],
		];
		yield 'wgContentHandlerUseDB false, opts user' => [
			[
				'wgContentHandlerUseDB' => false,
			],
			[ 'user' ],
			[
				'tables' => [ 'revision', 'commentstore' => 'table', 'actormigration' => 'table', 'user' ],
				'fields' => [
					'rev_id',
					'rev_page',
					'rev_text_id',
					'rev_timestamp',
					'rev_minor_edit',
					'rev_deleted',
					'rev_len',
					'rev_parent_id',
					'rev_sha1',
					'commentstore' => 'field',
					'rev_user' => 'actormigration_user',
					'rev_user_text' => 'actormigration_user_text',
					'rev_actor' => 'actormigration_actor',
					'user_name',
				],
				'joins' => [
					'user' => [
						'LEFT JOIN',
						[
							'actormigration_user != 0',
							'user_id = actormigration_user',
						],
					],
					'commentstore' => 'join',
					'actormigration' => 'join',
				],
			],
		];
		yield 'wgContentHandlerUseDB false, opts text' => [
			[
				'wgContentHandlerUseDB' => false,
			],
			[ 'text' ],
			[
				'tables' => [ 'revision', 'commentstore' => 'table', 'actormigration' => 'table', 'text' ],
				'fields' => [
					'rev_id',
					'rev_page',
					'rev_text_id',
					'rev_timestamp',
					'rev_minor_edit',
					'rev_deleted',
					'rev_len',
					'rev_parent_id',
					'rev_sha1',
					'commentstore' => 'field',
					'rev_user' => 'actormigration_user',
					'rev_user_text' => 'actormigration_user_text',
					'rev_actor' => 'actormigration_actor',
					'old_text',
					'old_flags',
				],
				'joins' => [
					'text' => [
						'INNER JOIN',
						[ 'rev_text_id=old_id' ],
					],
					'commentstore' => 'join',
					'actormigration' => 'join',
				],
			],
		];
		yield 'wgContentHandlerUseDB false, opts 3' => [
			[
				'wgContentHandlerUseDB' => false,
			],
			[ 'text', 'page', 'user' ],
			[
				'tables' => [
					'revision', 'commentstore' => 'table', 'actormigration' => 'table', 'page', 'user', 'text'
				],
				'fields' => [
					'rev_id',
					'rev_page',
					'rev_text_id',
					'rev_timestamp',
					'rev_minor_edit',
					'rev_deleted',
					'rev_len',
					'rev_parent_id',
					'rev_sha1',
					'commentstore' => 'field',
					'rev_user' => 'actormigration_user',
					'rev_user_text' => 'actormigration_user_text',
					'rev_actor' => 'actormigration_actor',
					'page_namespace',
					'page_title',
					'page_id',
					'page_latest',
					'page_is_redirect',
					'page_len',
					'user_name',
					'old_text',
					'old_flags',
				],
				'joins' => [
					'page' => [
						'INNER JOIN',
						[ 'page_id = rev_page' ],
					],
					'user' => [
						'LEFT JOIN',
						[
							'actormigration_user != 0',
							'user_id = actormigration_user',
						],
					],
					'text' => [
						'INNER JOIN',
						[ 'rev_text_id=old_id' ],
					],
					'commentstore' => 'join',
					'actormigration' => 'join',
				],
			],
		];
		yield 'wgContentHandlerUseDB true, opts none' => [
			[
				'wgContentHandlerUseDB' => true,
			],
			[],
			[
				'tables' => [ 'revision', 'commentstore' => 'table', 'actormigration' => 'table' ],
				'fields' => [
					'rev_id',
					'rev_page',
					'rev_text_id',
					'rev_timestamp',
					'rev_minor_edit',
					'rev_deleted',
					'rev_len',
					'rev_parent_id',
					'rev_sha1',
					'commentstore' => 'field',
					'rev_user' => 'actormigration_user',
					'rev_user_text' => 'actormigration_user_text',
					'rev_actor' => 'actormigration_actor',
					'rev_content_format',
					'rev_content_model',
				],
				'joins' => [ 'commentstore' => 'join', 'actormigration' => 'join' ],
			],
		];
	}

	/**
	 * @covers Revision::getQueryInfo
	 * @dataProvider provideGetQueryInfo
	 */
	public function testGetQueryInfo( $globals, $options, $expected ) {
		$this->setMwGlobals( $globals );
		$this->overrideCommentStoreAndActorMigration();

		$revisionStore = $this->getRevisionStore();
		$revisionStore->setContentHandlerUseDB( $globals['wgContentHandlerUseDB'] );
		$this->setService( 'RevisionStore', $revisionStore );

		$this->assertEquals(
			$expected,
			Revision::getQueryInfo( $options )
		);
	}

	/**
	 * @covers Revision::getSize
	 */
	public function testGetSize() {
		$title = $this->getMockTitle();

		$rec = new MutableRevisionRecord( $title );
		$rev = new Revision( $rec, 0, $title );

		$this->assertSame( 0, $rev->getSize(), 'Size of no slots is 0' );

		$rec->setSize( 13 );
		$this->assertSame( 13, $rev->getSize() );
	}

	/**
	 * @covers Revision::getSize
	 */
	public function testGetSize_failure() {
		$title = $this->getMockTitle();

		$rec = $this->getMockBuilder( RevisionRecord::class )
			->disableOriginalConstructor()
			->getMock();

		$rec->method( 'getSize' )
			->willThrowException( new RevisionAccessException( 'Oops!' ) );

		$rev = new Revision( $rec, 0, $title );
		$this->assertNull( $rev->getSize() );
	}

	/**
	 * @covers Revision::getSha1
	 */
	public function testGetSha1() {
		$title = $this->getMockTitle();

		$rec = new MutableRevisionRecord( $title );
		$rev = new Revision( $rec, 0, $title );

		$emptyHash = SlotRecord::base36Sha1( '' );
		$this->assertSame( $emptyHash, $rev->getSha1(), 'Sha1 of no slots is hash of empty string' );

		$rec->setSha1( 'deadbeef' );
		$this->assertSame( 'deadbeef', $rev->getSha1() );
	}

	/**
	 * @covers Revision::getSha1
	 */
	public function testGetSha1_failure() {
		$title = $this->getMockTitle();

		$rec = $this->getMockBuilder( RevisionRecord::class )
			->disableOriginalConstructor()
			->getMock();

		$rec->method( 'getSha1' )
			->willThrowException( new RevisionAccessException( 'Oops!' ) );

		$rev = new Revision( $rec, 0, $title );
		$this->assertNull( $rev->getSha1() );
	}

	/**
	 * @covers Revision::getContent
	 */
	public function testGetContent() {
		$title = $this->getMockTitle();

		$rec = new MutableRevisionRecord( $title );
		$rev = new Revision( $rec, 0, $title );

		$this->assertNull( $rev->getContent(), 'Content of no slots is null' );

		$content = new TextContent( 'Hello Kittens!' );
		$rec->setContent( 'main', $content );
		$this->assertSame( $content, $rev->getContent() );
	}

	/**
	 * @covers Revision::getContent
	 */
	public function testGetContent_failure() {
		$title = $this->getMockTitle();

		$rec = $this->getMockBuilder( RevisionRecord::class )
			->disableOriginalConstructor()
			->getMock();

		$rec->method( 'getContent' )
			->willThrowException( new RevisionAccessException( 'Oops!' ) );

		$rev = new Revision( $rec, 0, $title );
		$this->assertNull( $rev->getContent() );
	}

}
