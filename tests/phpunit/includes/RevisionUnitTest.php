<?php

use Wikimedia\TestingAccessWrapper;

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
	 * @dataProvider provideConstructFromArray
	 */
	public function testConstructFromArray( $rowArray ) {
		$rev = new Revision( $rowArray );
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
			new MWException( 'Revision constructor passed invalid row format.' )
		];
	}

	/**
	 * @dataProvider provideConstructFromArrayThrowsExceptions
	 */
	public function testConstructFromArrayThrowsExceptions( $rowArray, Exception $expectedException ) {
		$this->setExpectedException(
			get_class( $expectedException ),
			$expectedException->getMessage(),
			$expectedException->getCode()
		);
		new Revision( $rowArray );
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
		$rev = new Revision( $rowArray );
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
		$rev = new Revision( [] );
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
		$rev = new Revision( [] );
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
		$rev = new Revision( $rowArray );
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
		$rev = new Revision( $rowArray );
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

	public function provideGetRevisionTextWithLegacyEncoding() {
		yield 'Utf8Native' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'iso-8859-1',
			[
				'old_flags' => 'utf-8',
				'old_text' => "Wiki est l'\xc3\xa9cole superieur !",
			]
		];
		yield 'Utf8Legacy' => [
			"Wiki est l'\xc3\xa9cole superieur !",
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
	public function testGetRevisionWithLegacyEncoding( $expected, $encoding, $rowData ) {
		$this->setMwGlobals( 'wgLegacyEncoding', $encoding );
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
			'iso-8859-1',
			[
				'old_flags' => 'gzip,utf-8',
				'old_text' => gzdeflate( "Wiki est l'\xc3\xa9cole superieur !" ),
			]
		];
		yield 'Utf8LegacyGzip' => [
			"Wiki est l'\xc3\xa9cole superieur !",
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
	public function testGetRevisionWithGzipAndLegacyEncoding( $expected, $encoding, $rowData ) {
		$this->checkPHPExtension( 'zlib' );
		$this->setMwGlobals( 'wgLegacyEncoding', $encoding );
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
		$this->setMwGlobals( 'wgCompressRevisions', true );

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
		$this->setMwGlobals( 'wgContentHandlerUseDB', $contentHandlerUseDB );
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
		$this->setMwGlobals( 'wgContentHandlerUseDB', $contentHandlerUseDB );
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

	public function provideFetchFromConds() {
		yield [ 0, [] ];
		yield [ Revision::READ_LOCKING, [ 'FOR UPDATE' ] ];
	}

	/**
	 * @dataProvider provideFetchFromConds
	 * @covers Revision::fetchFromConds
	 */
	public function testFetchFromConds( $flags, $options ) {
		$conditions = [ 'conditionsArray' ];

		$db = $this->getMock( IDatabase::class );
		$db->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				$this->equalTo( [ 'revision', 'page', 'user' ] ),
				// We don't really care about the fields are they come from the selectField methods
				$this->isType( 'array' ),
				$this->equalTo( $conditions ),
				// Method name
				$this->equalTo( 'Revision::fetchFromConds' ),
				$this->equalTo( $options ),
				// We don't really care about the join conds are they come from the joinCond methods
				$this->isType( 'array' )
			)
			->willReturn( 'RETURNVALUE' );

		$wrapper = TestingAccessWrapper::newFromClass( Revision::class );
		$result = $wrapper->fetchFromConds( $db, $conditions, $flags );

		$this->assertEquals( 'RETURNVALUE', $result );
	}
}
