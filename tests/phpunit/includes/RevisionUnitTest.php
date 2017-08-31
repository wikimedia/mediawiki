<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\RevisionStore;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @group ContentHandler
 */
class RevisionUnitTest extends MediaWikiTestCase {

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
			->will( $this->returnValue( 0 ) );
		$mock->expects( $this->any() )
			->method( 'getPrefixedText' )
			->will( $this->returnValue( 'RevisionTest' ) );
		$mock->expects( $this->any() )
			->method( 'getDBKey' )
			->will( $this->returnValue( 'RevisionTest' ) );
		$mock->expects( $this->any() )
			->method( 'getArticleID' )
			->will( $this->returnValue( 23 ) );
		$mock->expects( $this->any() )
			->method( 'getModel' )
			->will( $this->returnValue( $model ) );

		return $mock;
	}

	/**
	 * @dataProvider provideConstructFromArray
	 * @covers Revision::__construct
	 * @covers Revision::constructFromRowArray
	 */
	public function testConstructFromArray( array $rowArray ) {
		$rev = new Revision( $rowArray, 0, $this->getMockTitle() );
		$this->assertNotNull( $rev->getContent(), 'no content object available' );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContent()->getModel() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContentModel() );
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
			new MWException( '`content` field must contain a Content object.' )
		];
		yield 'with bad content object (string)' => [
			[ 'content' => 'ImAGoat' ],
			new MWException( '`content` field must contain a Content object.' )
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
	 * @covers Revision::constructFromRowArray
	 */
	public function testConstructFromArrayThrowsExceptions( $rowArray, Exception $expectedException ) {
		$this->setExpectedException(
			get_class( $expectedException ),
			$expectedException->getMessage(),
			$expectedException->getCode()
		);
		new Revision( $rowArray, 0, $this->getMockTitle() );
	}

	public function provideConstructFromRow() {
		yield 'Full construction' => [
			[
				'rev_id' => '2',
				'rev_page' => '1',
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
			function ( RevisionUnitTest $testCase, Revision $rev ) {
				$testCase->assertSame( 2, $rev->getId() );
				$testCase->assertSame( 1, $rev->getPage() );
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
		yield 'null fields' => [
			[
				'rev_id' => '2',
				'rev_page' => '1',
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
			function ( RevisionUnitTest $testCase, Revision $rev ) {
				$testCase->assertNull( $rev->getSize() );
				$testCase->assertNull( $rev->getParentId() );
				$testCase->assertNull( $rev->getSha1() );
				$testCase->assertSame( 'text/x-wiki', $rev->getContentFormat() );
				$testCase->assertSame( 'wikitext', $rev->getContentModel() );
			}
		];
	}

	/**
	 * @dataProvider provideConstructFromRow
	 * @covers Revision::__construct
	 * @covers Revision::constructFromDbRowObject
	 */
	public function testConstructFromRow( array $arrayData, $assertions ) {
		$row = (object)$arrayData;
		$rev = new Revision( $row );
		$assertions( $this, $rev );
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
		yield [ [ 'text_id' => '123' ], 'tt:123' ];
		yield [ [ 'text_id' => 456 ], 'tt:456' ];
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

	/**
	 * @return BlobStore
	 */
	private function getBlobStore() {
		/** @var LoadBalancer $lb */
		$lb = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();

		$blobStore = new BlobStore( $lb, $cache );
		return $blobStore;
	}

	/**
	 * @return RevisionStore
	 */
	private function getRevisionStore() {
		/** @var LoadBalancer $lb */
		$lb = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();

		$blobStore = new RevisionStore( $lb, $this->getBlobStore(), $cache );
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
		$this->setService( 'BlobStore', $blobStore );

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
		$this->setService( 'BlobStore', $blobStore );

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
		$blobStore->setCompressRevisions( true );
		$this->setService( 'BlobStore', $blobStore );

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
	 * @covers Revision::userJoinCond
	 */
	public function testUserJoinCond() {
		$this->assertEquals(
			[ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
			Revision::userJoinCond()
		);
	}

	/**
	 * @covers Revision::pageJoinCond
	 */
	public function testPageJoinCond() {
		$this->assertEquals(
			[ 'INNER JOIN', [ 'page_id = rev_page' ] ],
			Revision::pageJoinCond()
		);
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
				'rev_minor_edit',
				'rev_deleted',
				'rev_len',
				'rev_parent_id',
				'rev_sha1',
				'rev_comment_text' => 'rev_comment',
				'rev_comment_data' => 'NULL',
				'rev_comment_cid' => 'NULL',
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
				'rev_minor_edit',
				'rev_deleted',
				'rev_len',
				'rev_parent_id',
				'rev_sha1',
				'rev_comment_text' => 'rev_comment',
				'rev_comment_data' => 'NULL',
				'rev_comment_cid' => 'NULL',
			]
		];
	}

	/**
	 * @dataProvider provideSelectFields
	 * @covers Revision::selectFields
	 * @todo a true unit test would mock CommentStore
	 */
	public function testSelectFields( $contentHandlerUseDB, $expected ) {
		$revisionStore = $this->getRevisionStore();
		$revisionStore->setContentHandlerUseDB( $contentHandlerUseDB );
		$this->setService( 'RevisionStore', $revisionStore );

		$this->assertEquals( $expected, Revision::selectFields() );
	}

	public function provideSelectArchiveFields() {
		yield [
			true,
			[
				'ar_id',
				'ar_page_id',
				'ar_rev_id',
				'ar_text',
				'ar_text_id',
				'ar_timestamp',
				'ar_user_text',
				'ar_user',
				'ar_minor_edit',
				'ar_deleted',
				'ar_len',
				'ar_parent_id',
				'ar_sha1',
				'ar_comment_text' => 'ar_comment',
				'ar_comment_data' => 'NULL',
				'ar_comment_cid' => 'NULL',
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
				'ar_text',
				'ar_text_id',
				'ar_timestamp',
				'ar_user_text',
				'ar_user',
				'ar_minor_edit',
				'ar_deleted',
				'ar_len',
				'ar_parent_id',
				'ar_sha1',
				'ar_comment_text' => 'ar_comment',
				'ar_comment_data' => 'NULL',
				'ar_comment_cid' => 'NULL',
			]
		];
	}

	/**
	 * @dataProvider provideSelectArchiveFields
	 * @covers Revision::selectArchiveFields
	 * @todo a true unit test would mock CommentStore
	 */
	public function testSelectArchiveFields( $contentHandlerUseDB, $expected ) {
		$revisionStore = $this->getRevisionStore();
		$revisionStore->setContentHandlerUseDB( $contentHandlerUseDB );
		$this->setService( 'RevisionStore', $revisionStore );

		$this->assertEquals( $expected, Revision::selectArchiveFields() );
	}

	/**
	 * @covers Revision::selectTextFields
	 */
	public function testSelectTextFields() {
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
		$this->assertEquals(
			[
				'user_name',
			],
			Revision::selectUserFields()
		);
	}

	/**
	 * @covers Revision::loadFromTitle
	 */
	public function testLoadFromTitle() {
		$title = $this->getMockTitle();

		$conditions = [
			0 => 'rev_id=page_latest',
			'page_namespace' => $title->getNamespace(),
			'page_title' => 'RevisionTest',
		];

		$row = (object)[
			'rev_id' => 17,
			'rev_timestamp' => '20200202202020',
			'rev_page' => $title->getArticleID(),
			'rev_parent' => 7,
			'rev_len' => 678,
			'rev_sha1' => null,
			'rev_text_id' => 700,
			'rev_comment_text' => 'Testing',
			'rev_comment_data' => null,
			'rev_user' => 3,
			'rev_user_text' => 'Tester',
			'rev_minor_edit' => 0,
			'rev_deleted' => 0,
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

		$result = Revision::loadFromTitle( $db, $title );

		$this->assertEquals( $title->getArticleID(), $result->getTitle()->getArticleID() );
		$this->assertEquals( $row->rev_id, $result->getId() );
		$this->assertEquals( $row->rev_len, $result->getSize() );
		$this->assertEquals( $row->rev_timestamp, $result->getTimestamp() );
		$this->assertEquals( $row->rev_comment_text, $result->getComment() );
		$this->assertEquals( $row->rev_user_text, $result->getUserText() );
	}
}
