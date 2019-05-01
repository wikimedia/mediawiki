<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Test cases in RevisionTest should not interact with the Database.
 * For test cases that need Database interaction see RevisionDbTestBase.
 */
class RevisionTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();
		$this->setMwGlobals(
			'wgMultiContentRevisionSchemaMigrationStage',
			SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW
		);
	}

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
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromArray( $rowArray ) {
		$rev = new Revision( $rowArray, 0, $this->getMockTitle() );
		$this->assertNotNull( $rev->getContent(), 'no content object available' );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContent()->getModel() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContentModel() );
	}

	/**
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromEmptyArray() {
		$rev = new Revision( [], 0, $this->getMockTitle() );
		$this->assertNull( $rev->getContent(), 'no content object should be available' );
	}

	/**
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
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
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
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
			new MWException( 'The text_id field is only available in the pre-MCR schema' )
		];

		yield 'with bad content object (class)' => [
			[ 'content' => new stdClass() ],
			new MWException( 'content field must contain a Content object' )
		];
		yield 'with bad content object (string)' => [
			[ 'content' => 'ImAGoat' ],
			new MWException( 'content field must contain a Content object' )
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
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
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
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
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
			],
			function ( RevisionTest $testCase, Revision $rev ) {
				$testCase->assertSame( 42, $rev->getId() );
				$testCase->assertSame( 23, $rev->getPage() );
				$testCase->assertSame( '20171017114835', $rev->getTimestamp() );
				$testCase->assertSame( '127.0.0.1', $rev->getUserText() );
				$testCase->assertSame( 0, $rev->getUser() );
				$testCase->assertSame( false, $rev->isMinor() );
				$testCase->assertSame( false, $rev->isDeleted( Revision::DELETED_TEXT ) );
				$testCase->assertSame( 46, $rev->getSize() );
				$testCase->assertSame( 1, $rev->getParentId() );
				$testCase->assertSame( 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z', $rev->getSha1() );
				$testCase->assertSame( 'Goat Comment!', $rev->getComment() );
			}
		];
		yield 'default field values' => [
			[
				'rev_id' => '42',
				'rev_page' => '23',
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
			}
		];
	}

	/**
	 * @dataProvider provideConstructFromRow
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromRow( array $arrayData, callable $assertions ) {
		$row = (object)$arrayData;
		$rev = new Revision( $row, 0, $this->getMockTitle() );
		$assertions( $this, $rev );
	}

	/**
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromRowWithBadPageId() {
		$this->overrideMwServices();
		Wikimedia\suppressWarnings();
		$rev = new Revision( (object)[
			'rev_page' => 77777777,
			'rev_comment_text' => '',
			'rev_comment_data' => null,
		] );
		$this->assertSame( 77777777, $rev->getPage() );
		Wikimedia\restoreWarnings();
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

	public function provideGetRevisionText() {
		yield 'Generic test' => [
			'This is a goat of revision text.',
			(object)[
				'old_flags' => '',
				'old_text' => 'This is a goat of revision text.',
			],
		];
		yield 'garbage in, garbage out' => [
			false,
			false,
		];
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionText
	 */
	public function testGetRevisionText( $expected, $rowData, $prefix = 'old_', $wiki = false ) {
		$this->assertEquals(
			$expected,
			Revision::getRevisionText( $rowData, $prefix, $wiki ) );
	}

	public function provideGetRevisionTextWithZlibExtension() {
		yield 'Generic gzip test' => [
			'This is a small goat of revision text.',
			(object)[
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

	public function provideGetRevisionTextWithZlibExtension_badData() {
		yield 'Generic gzip test' => [
			'This is a small goat of revision text.',
			(object)[
				'old_flags' => 'gzip',
				'old_text' => 'DEAD BEEF',
			],
		];
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionTextWithZlibExtension_badData
	 */
	public function testGetRevisionWithZlibExtension_badData( $expected, $rowData ) {
		$this->checkPHPExtension( 'zlib' );
		Wikimedia\suppressWarnings();
		$this->assertFalse(
			Revision::getRevisionText(
				(object)$rowData
			)
		);
		Wikimedia\suppressWarnings( true );
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
			MediaWikiServices::getInstance()->getContentModelStore(),
			MediaWikiServices::getInstance()->getSlotRoleStore(),
			MediaWikiServices::getInstance()->getSlotRoleRegistry(),
			MIGRATION_OLD,
			MediaWikiServices::getInstance()->getActorMigration()
		);
		return $blobStore;
	}

	public function provideGetRevisionTextWithLegacyEncoding() {
		yield 'Utf8Native' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'fr',
			'iso-8859-1',
			(object)[
				'old_flags' => 'utf-8',
				'old_text' => "Wiki est l'\xc3\xa9cole superieur !",
			]
		];
		yield 'Utf8Legacy' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'fr',
			'iso-8859-1',
			(object)[
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
			(object)[
				'old_flags' => 'gzip,utf-8',
				'old_text' => gzdeflate( "Wiki est l'\xc3\xa9cole superieur !" ),
			]
		];
		yield 'Utf8LegacyGzip' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'fr',
			'iso-8859-1',
			(object)[
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
		$this->assertTrue( strpos( $row->old_flags, 'utf-8' ) !== false,
			"Flags should contain 'utf-8'" );
		$this->assertFalse( strpos( $row->old_flags, 'gzip' ) !== false,
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
		$this->assertTrue( strpos( $row->old_flags, 'utf-8' ) !== false,
			"Flags should contain 'utf-8'" );
		$this->assertTrue( strpos( $row->old_flags, 'gzip' ) !== false,
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
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', SCHEMA_COMPAT_NEW );
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

		$domain = MediaWikiServices::getInstance()->getDBLoadBalancer()->getLocalDomainID();
		$db = $this->getMock( IDatabase::class );
		$db->expects( $this->any() )
			->method( 'getDomainId' )
			->will( $this->returnValue( $domain ) );
		$db->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				$this->equalTo( [
					'revision', 'page', 'user',
					'temp_rev_comment' => 'revision_comment_temp', 'comment_rev_comment' => 'comment',
					'temp_rev_user' => 'revision_actor_temp', 'actor_rev_user' => 'actor',
				] ),
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
		Wikimedia\suppressWarnings();
		$this->assertFalse(
			Revision::getRevisionText(
				(object)[
					'old_text' => $text,
					'old_flags' => 'external',
				]
			)
		);
		Wikimedia\suppressWarnings( true );
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

		$cacheKey = $cache->makeGlobalKey(
			'BlobStore',
			'address',
			$lb->getLocalDomainID(),
			'tt:7777'
		);
		$this->assertSame( 'AAAABBAAA', $cache->get( $cacheKey ) );
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
		$rec->setContent( SlotRecord::MAIN, $content );
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
