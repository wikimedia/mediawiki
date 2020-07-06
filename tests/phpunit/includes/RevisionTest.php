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
class RevisionTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();

		// Rather than adding these to all of the individual tests
		$this->hideDeprecated( 'Revision::getRevisionRecord' );
		$this->hideDeprecated( 'Revision::getId' );
		$this->hideDeprecated( 'Revision::getTitle' );
		$this->hideDeprecated( 'Revision::getUser' );
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
		$this->hideDeprecated( 'Revision::getContentModel' );
		$this->hideDeprecated( 'Revision::getContent' );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::getContent' );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::getUserText' );
		$this->hideDeprecated( 'Revision::__construct' );

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
			new MWException( 'The text_id field can not be used in MediaWiki 1.35 and later' )
		];

		yield 'with bad content object (class)' => [
			[ 'content' => (object)[] ],
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
		$this->hideDeprecated( 'Revision::__construct' );

		$this->expectException( get_class( $expectedException ) );
		$this->expectExceptionMessage( $expectedException->getMessage() );
		$this->expectExceptionCode( $expectedException->getCode() );
		new Revision( $rowArray, 0, $this->getMockTitle() );
	}

	/**
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromNothing() {
		$this->hideDeprecated( 'Revision::__construct' );

		$this->expectException( InvalidArgumentException::class );
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
				$testCase->hideDeprecated( 'Revision::getSha1' );
				$testCase->hideDeprecated( 'Revision::getUserText' );
				$testCase->hideDeprecated( 'Revision::isMinor' );
				$testCase->hideDeprecated( 'Revision::getParentId' );
				$testCase->hideDeprecated( 'Revision::isDeleted' );
				$testCase->hideDeprecated( 'Revision::getPage' );
				$testCase->hideDeprecated( 'Revision::getComment' );
				$testCase->hideDeprecated( 'Revision::getSize' );
				$testCase->hideDeprecated( 'Revision::getTimestamp' );

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
				$testCase->hideDeprecated( 'Revision::getUserText' );
				$testCase->hideDeprecated( 'Revision::isMinor' );
				$testCase->hideDeprecated( 'Revision::getParentId' );
				$testCase->hideDeprecated( 'Revision::getVisibility' );
				$testCase->hideDeprecated( 'Revision::getComment' );
				$testCase->hideDeprecated( 'Revision::getTimestamp' );

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
		$this->hideDeprecated( 'Revision::__construct' );

		$row = (object)$arrayData;
		$rev = new Revision( $row, 0, $this->getMockTitle() );
		$assertions( $this, $rev );
	}

	/**
	 * @covers Revision::__construct
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
	 */
	public function testConstructFromRowWithBadPageId() {
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
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::setId' );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::setUserIdAndName' );
		$this->hideDeprecated( 'Revision::getUserText' );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::getParentId' );
		$this->hideDeprecated( 'Revision::__construct' );

		$rev = new Revision( $rowArray, 0, $this->getMockTitle() );
		$this->assertSame( $expected, $rev->getParentId() );
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
		$access = MediaWikiServices::getInstance()->getExternalStoreAccess();
		$cache = $this->getWANObjectCache();

		$blobStore = new SqlBlobStore( $lb, $access, $cache );

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
	 * @covers Revision::compressRevisionText
	 */
	public function testCompressRevisionTextUtf8() {
		$this->hideDeprecated( 'Revision::compressRevisionText' );
		$this->hideDeprecated( 'Revision::decompressRevisionText' );

		$row = (object)[ 'old_text' => "Wiki est l'\xc3\xa9cole superieur !" ];
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertTrue( strpos( $row->old_flags, 'utf-8' ) !== false,
			"Flags should contain 'utf-8'" );
		$this->assertFalse( strpos( $row->old_flags, 'gzip' ) !== false,
			"Flags should not contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			$row->old_text, "Direct check" );
		$this->hideDeprecated( 'Revision::getRevisionText' );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			Revision::decompressRevisionText( $row->old_text, explode( ',', $row->old_flags ) ),
			"decompressRevisionText" );
	}

	/**
	 * @covers Revision::compressRevisionText
	 */
	public function testCompressRevisionTextUtf8Gzip() {
		$this->hideDeprecated( 'Revision::compressRevisionText' );
		$this->hideDeprecated( 'Revision::decompressRevisionText' );

		$this->checkPHPExtension( 'zlib' );

		$blobStore = $this->getBlobStore();
		$blobStore->setCompressBlobs( true );
		$this->setService( 'BlobStoreFactory', $this->mockBlobStoreFactory( $blobStore ) );

		$row = (object)[ 'old_text' => "Wiki est l'\xc3\xa9cole superieur !" ];
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertTrue( strpos( $row->old_flags, 'utf-8' ) !== false,
			"Flags should contain 'utf-8'" );
		$this->assertTrue( strpos( $row->old_flags, 'gzip' ) !== false,
			"Flags should contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			gzinflate( $row->old_text ), "Direct check" );
		$this->hideDeprecated( 'Revision::getRevisionText' );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			Revision::decompressRevisionText( $row->old_text, explode( ',', $row->old_flags ) ),
			"decompressRevisionText" );
	}

	/**
	 * @covers Revision::loadFromTitle
	 */
	public function testLoadFromTitle() {
		$this->hideDeprecated( 'Revision::loadFromTitle' );
		$this->hideDeprecated( 'Revision::getSha1' );
		$this->hideDeprecated( 'Revision::getUserText' );
		$this->hideDeprecated( 'Revision::getParentId' );
		$this->hideDeprecated( 'Revision::getComment' );
		$this->hideDeprecated( 'Revision::getSize' );
		$this->hideDeprecated( 'Revision::getTimestamp' );
		$this->hideDeprecated( 'Revision::__construct' );
		$this->hideDeprecated( RevisionStore::class . '::loadRevisionFromTitle' );

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
		$db = $this->createMock( IDatabase::class );
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
		yield '(no legacy encoding), empty in empty out 2' => [ false, 'A', [], 'A' ];
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
		$this->hideDeprecated( 'Revision::decompressRevisionText' );

		$blobStore = $this->getBlobStore();
		if ( $legacyEncoding ) {
			$blobStore->setLegacyEncoding( $legacyEncoding );
		}

		$this->setService( 'BlobStoreFactory', $this->mockBlobStoreFactory( $blobStore ) );
		$this->assertSame(
			$expected,
			Revision::decompressRevisionText( $text, $flags )
		);
	}

	/**
	 * @covers Revision::getSize
	 */
	public function testGetSize() {
		$this->hideDeprecated( 'Revision::getSize' );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::getSize' );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::getSha1' );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::getSha1' );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::getContent' );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->hideDeprecated( 'Revision::getContent' );
		$this->hideDeprecated( 'Revision::__construct' );

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
