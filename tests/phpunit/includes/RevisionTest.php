<?php

use Wikimedia\TestingAccessWrapper;

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
	}

	/**
	 * @dataProvider provideConstructFromArray
	 * @covers Revision::__construct
	 * @covers Revision::constructFromRowArray
	 */
	public function testConstructFromArray( array $rowArray ) {
		$rev = new Revision( $rowArray );
		$this->assertNotNull( $rev->getContent(), 'no content object available' );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContent()->getModel() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContentModel() );
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
		// Note: the below XXX test cases are odd and probably result in unexpected behaviour if used
		// in production code.
		yield 'XXX: user text only' => [
			[
				'content' => new JavaScriptContent( 'hello world.' ),
				'user_text' => '111.111.111.111',
			],
			null,
			'111.111.111.111',
		];
		yield 'XXX: user id only' => [
			[
				'content' => new JavaScriptContent( 'hello world.' ),
				'user' => 9989,
			],
			9989,
			null,
		];
	}

	/**
	 * @dataProvider provideConstructFromArray_userSetAsExpected
	 * @covers Revision::__construct
	 * @covers Revision::constructFromRowArray
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

		$rev = new Revision( $rowArray );
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
	 * @covers Revision::__construct
	 * @covers Revision::constructFromRowArray
	 */
	public function testConstructFromArrayThrowsExceptions( $rowArray, Exception $expectedException ) {
		$this->setExpectedException(
			get_class( $expectedException ),
			$expectedException->getMessage(),
			$expectedException->getCode()
		);
		new Revision( $rowArray );
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
			function ( RevisionTest $testCase, Revision $rev ) {
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
			function ( RevisionTest $testCase, Revision $rev ) {
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

	public function provideFetchFromConds() {
		yield [ 0, [] ];
		yield [ Revision::READ_LOCKING, [ 'FOR UPDATE' ] ];
	}

	/**
	 * @dataProvider provideFetchFromConds
	 * @covers Revision::fetchFromConds
	 */
	public function testFetchFromConds( $flags, array $options ) {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
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
		$this->setMwGlobals( 'wgLegacyEncoding', $legacyEncoding );
		$this->setMwGlobals( 'wgLanguageCode', 'en' );
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
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );
		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ] )
		);

		$cacheKey = $cache->makeKey( 'revisiontext', 'textid', '7777' );

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
		$this->assertSame( 'AAAABBAAA', $cache->get( $cacheKey ) );
	}

	/**
	 * @covers Revision::userJoinCond
	 */
	public function testUserJoinCond() {
		$this->hideDeprecated( 'Revision::userJoinCond' );
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
		$this->hideDeprecated( 'Revision::selectFields' );
		$this->setMwGlobals( 'wgContentHandlerUseDB', $contentHandlerUseDB );
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
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
		$this->hideDeprecated( 'Revision::selectArchiveFields' );
		$this->setMwGlobals( 'wgContentHandlerUseDB', $contentHandlerUseDB );
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
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
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage OLD' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_OLD,
			],
			[
				'tables' => [ 'archive' ],
				'fields' => [
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
				],
				'joins' => [],
			]
		];
		yield 'wgContentHandlerUseDB true, wgCommentTableSchemaMigrationStage OLD' => [
			[
				'wgContentHandlerUseDB' => true,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_OLD,
			],
			[
				'tables' => [ 'archive' ],
				'fields' => [
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
				],
				'joins' => [],
			]
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage WRITE_BOTH' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_WRITE_BOTH,
			],
			[
				'tables' => [
					'archive',
					'comment_ar_comment' => 'comment',
				],
				'fields' => [
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
					'ar_comment_text' => 'COALESCE( comment_ar_comment.comment_text, ar_comment )',
					'ar_comment_data' => 'comment_ar_comment.comment_data',
					'ar_comment_cid' => 'comment_ar_comment.comment_id',
				],
				'joins' => [
					'comment_ar_comment' => [
						'LEFT JOIN',
						'comment_ar_comment.comment_id = ar_comment_id',
					],
				],
			]
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage WRITE_NEW' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_WRITE_NEW,
			],
			[
				'tables' => [
					'archive',
					'comment_ar_comment' => 'comment',
				],
				'fields' => [
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
					'ar_comment_text' => 'COALESCE( comment_ar_comment.comment_text, ar_comment )',
					'ar_comment_data' => 'comment_ar_comment.comment_data',
					'ar_comment_cid' => 'comment_ar_comment.comment_id',
				],
				'joins' => [
					'comment_ar_comment' => [
						'LEFT JOIN',
						'comment_ar_comment.comment_id = ar_comment_id',
					],
				],
			]
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage NEW' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_NEW,
			],
			[
				'tables' => [
					'archive',
					'comment_ar_comment' => 'comment',
				],
				'fields' => [
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
					'ar_comment_text' => 'comment_ar_comment.comment_text',
					'ar_comment_data' => 'comment_ar_comment.comment_data',
					'ar_comment_cid' => 'comment_ar_comment.comment_id',
				],
				'joins' => [
					'comment_ar_comment' => [
						'JOIN',
						'comment_ar_comment.comment_id = ar_comment_id',
					],
				],
			]
		];
	}

	/**
	 * @covers Revision::getArchiveQueryInfo
	 * @dataProvider provideGetArchiveQueryInfo
	 */
	public function testGetArchiveQueryInfo( $globals, $expected ) {
		$this->setMwGlobals( $globals );
		$this->assertEquals(
			$expected,
			Revision::getArchiveQueryInfo()
		);
	}

	public function provideGetQueryInfo() {
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage OLD, opts none' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_OLD,
			],
			[],
			[
				'tables' => [ 'revision' ],
				'fields' => [
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
				],
				'joins' => [],
			],
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage OLD, opts page' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_OLD,
			],
			[ 'page' ],
			[
				'tables' => [ 'revision', 'page' ],
				'fields' => [
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
				],
			],
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage OLD, opts user' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_OLD,
			],
			[ 'user' ],
			[
				'tables' => [ 'revision', 'user' ],
				'fields' => [
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
					'user_name',
				],
				'joins' => [
					'user' => [
						'LEFT JOIN',
						[
							'rev_user != 0',
							'user_id = rev_user',
						],
					],
				],
			],
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage OLD, opts text' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_OLD,
			],
			[ 'text' ],
			[
				'tables' => [ 'revision', 'text' ],
				'fields' => [
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
					'old_text',
					'old_flags',
				],
				'joins' => [
					'text' => [
						'INNER JOIN',
						[ 'rev_text_id=old_id' ],
					],
				],
			],
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage OLD, opts 3' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_OLD,
			],
			[ 'text', 'page', 'user' ],
			[
				'tables' => [ 'revision', 'page', 'user', 'text' ],
				'fields' => [
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
							'rev_user != 0',
							'user_id = rev_user',
						],
					],
					'text' => [
						'INNER JOIN',
						[ 'rev_text_id=old_id' ],
					],
				],
			],
		];
		yield 'wgContentHandlerUseDB true, wgCommentTableSchemaMigrationStage OLD, opts none' => [
			[
				'wgContentHandlerUseDB' => true,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_OLD,
			],
			[],
			[
				'tables' => [ 'revision' ],
				'fields' => [
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
				],
				'joins' => [],
			],
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage WRITE_BOTH, opts none' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_WRITE_BOTH,
			],
			[],
			[
				'tables' => [
					'revision',
					'temp_rev_comment' => 'revision_comment_temp',
					'comment_rev_comment' => 'comment',
				],
				'fields' => [
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
					'rev_comment_text' => 'COALESCE( comment_rev_comment.comment_text, rev_comment )',
					'rev_comment_data' => 'comment_rev_comment.comment_data',
					'rev_comment_cid' => 'comment_rev_comment.comment_id',
				],
				'joins' => [
					'temp_rev_comment' => [
						'LEFT JOIN',
						'temp_rev_comment.revcomment_rev = rev_id',
					],
					'comment_rev_comment' => [
						'LEFT JOIN',
						'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id',
					],
				],
			],
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage WRITE_NEW, opts none' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_WRITE_NEW,
			],
			[],
			[
				'tables' => [
					'revision',
					'temp_rev_comment' => 'revision_comment_temp',
					'comment_rev_comment' => 'comment',
				],
				'fields' => [
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
					'rev_comment_text' => 'COALESCE( comment_rev_comment.comment_text, rev_comment )',
					'rev_comment_data' => 'comment_rev_comment.comment_data',
					'rev_comment_cid' => 'comment_rev_comment.comment_id',
				],
				'joins' => [
					'temp_rev_comment' => [
						'LEFT JOIN',
						'temp_rev_comment.revcomment_rev = rev_id',
					],
					'comment_rev_comment' => [
						'LEFT JOIN',
						'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id',
					],
				],
			],
		];
		yield 'wgContentHandlerUseDB false, wgCommentTableSchemaMigrationStage NEW, opts none' => [
			[
				'wgContentHandlerUseDB' => false,
				'wgCommentTableSchemaMigrationStage' => MIGRATION_NEW,
			],
			[],
			[
				'tables' => [
					'revision',
					'temp_rev_comment' => 'revision_comment_temp',
					'comment_rev_comment' => 'comment',
				],
				'fields' => [
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
					'rev_comment_text' => 'comment_rev_comment.comment_text',
					'rev_comment_data' => 'comment_rev_comment.comment_data',
					'rev_comment_cid' => 'comment_rev_comment.comment_id',
				],
				'joins' => [
					'temp_rev_comment' => [
						'JOIN',
						'temp_rev_comment.revcomment_rev = rev_id',
					],
					'comment_rev_comment' => [
						'JOIN',
						'comment_rev_comment.comment_id = temp_rev_comment.revcomment_comment_id',
					],
				],
			],
		];
	}

	/**
	 * @covers Revision::getQueryInfo
	 * @dataProvider provideGetQueryInfo
	 */
	public function testGetQueryInfo( $globals, $options, $expected ) {
		$this->setMwGlobals( $globals );
		$this->assertEquals(
			$expected,
			Revision::getQueryInfo( $options )
		);
	}

}
