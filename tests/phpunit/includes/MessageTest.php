<?php

class MessageTest extends MediaWikiLangTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgForceUIMsgAsContentMsg' => [],
		] );
		$this->setUserLang( 'en' );
	}

	/**
	 * @covers Message::__construct
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $expectedLang, $key, $params, $language ) {
		$message = new Message( $key, $params, $language );

		$this->assertEquals( $key, $message->getKey() );
		$this->assertEquals( $params, $message->getParams() );
		$this->assertEquals( $expectedLang, $message->getLanguage() );

		$messageSpecifier = $this->getMockForAbstractClass( 'MessageSpecifier' );
		$messageSpecifier->expects( $this->any() )
			->method( 'getKey' )->will( $this->returnValue( $key ) );
		$messageSpecifier->expects( $this->any() )
			->method( 'getParams' )->will( $this->returnValue( $params ) );
		$message = new Message( $messageSpecifier, [], $language );

		$this->assertEquals( $key, $message->getKey() );
		$this->assertEquals( $params, $message->getParams() );
		$this->assertEquals( $expectedLang, $message->getLanguage() );
	}

	public static function provideConstructor() {
		$langDe = Language::factory( 'de' );
		$langEn = Language::factory( 'en' );

		return [
			[ $langDe, 'foo', [], $langDe ],
			[ $langDe, 'foo', [ 'bar' ], $langDe ],
			[ $langEn, 'foo', [ 'bar' ], null ]
		];
	}

	public static function provideConstructorParams() {
		return [
			[
				[],
				[],
			],
			[
				[ 'foo' ],
				[ 'foo' ],
			],
			[
				[ 'foo', 'bar' ],
				[ 'foo', 'bar' ],
			],
			[
				[ 'baz' ],
				[ [ 'baz' ] ],
			],
			[
				[ 'baz', 'foo' ],
				[ [ 'baz', 'foo' ] ],
			],
			[
				[ 'baz', 'foo' ],
				[ [ 'baz', 'foo' ], 'hhh' ],
			],
			[
				[ 'baz', 'foo' ],
				[ [ 'baz', 'foo' ], 'hhh', [ 'ahahahahha' ] ],
			],
			[
				[ 'baz', 'foo' ],
				[ [ 'baz', 'foo' ], [ 'ahahahahha' ] ],
			],
			[
				[ 'baz' ],
				[ [ 'baz' ], [ 'ahahahahha' ] ],
			],
		];
	}

	/**
	 * @covers Message::__construct
	 * @covers Message::getParams
	 * @dataProvider provideConstructorParams
	 */
	public function testConstructorParams( $expected, $args ) {
		$msg = new Message( 'imasomething' );

		$returned = call_user_func_array( [ $msg, 'params' ], $args );

		$this->assertSame( $msg, $returned );
		$this->assertEquals( $expected, $msg->getParams() );
	}

	public static function provideConstructorLanguage() {
		return [
			[ 'foo', [ 'bar' ], 'en' ],
			[ 'foo', [ 'bar' ], 'de' ]
		];
	}

	/**
	 * @covers Message::__construct
	 * @covers Message::getLanguage
	 * @dataProvider provideConstructorLanguage
	 */
	public function testConstructorLanguage( $key, $params, $languageCode ) {
		$language = Language::factory( $languageCode );
		$message = new Message( $key, $params, $language );

		$this->assertEquals( $language, $message->getLanguage() );
	}

	public static function provideKeys() {
		return [
			'string' => [
				'key' => 'mainpage',
				'expected' => [ 'mainpage' ],
			],
			'single' => [
				'key' => [ 'mainpage' ],
				'expected' => [ 'mainpage' ],
			],
			'multi' => [
				'key' => [ 'mainpage-foo', 'mainpage-bar', 'mainpage' ],
				'expected' => [ 'mainpage-foo', 'mainpage-bar', 'mainpage' ],
			],
			'empty' => [
				'key' => [],
				'expected' => null,
				'exception' => 'InvalidArgumentException',
			],
			'null' => [
				'key' => null,
				'expected' => null,
				'exception' => 'InvalidArgumentException',
			],
			'bad type' => [
				'key' => 123,
				'expected' => null,
				'exception' => 'InvalidArgumentException',
			],
		];
	}

	/**
	 * @covers Message::__construct
	 * @covers Message::getKey
	 * @covers Message::isMultiKey
	 * @covers Message::getKeysToTry
	 * @dataProvider provideKeys
	 */
	public function testKeys( $key, $expected, $exception = null ) {
		if ( $exception ) {
			$this->setExpectedException( $exception );
		}

		$msg = new Message( $key );
		$this->assertContains( $msg->getKey(), $expected );
		$this->assertEquals( $expected, $msg->getKeysToTry() );
		$this->assertEquals( count( $expected ) > 1, $msg->isMultiKey() );
	}

	/**
	 * @covers ::wfMessage
	 */
	public function testWfMessage() {
		$this->assertInstanceOf( 'Message', wfMessage( 'mainpage' ) );
		$this->assertInstanceOf( 'Message', wfMessage( 'i-dont-exist-evar' ) );
	}

	/**
	 * @covers Message::newFromKey
	 */
	public function testNewFromKey() {
		$this->assertInstanceOf( 'Message', Message::newFromKey( 'mainpage' ) );
		$this->assertInstanceOf( 'Message', Message::newFromKey( 'i-dont-exist-evar' ) );
	}

	/**
	 * @covers ::wfMessage
	 * @covers Message::__construct
	 */
	public function testWfMessageParams() {
		$this->assertEquals( 'Return to $1.', wfMessage( 'returnto' )->text() );
		$this->assertEquals( 'Return to $1.', wfMessage( 'returnto', [] )->text() );
		$this->assertEquals(
			'You have foo (bar).',
			wfMessage( 'youhavenewmessages', 'foo', 'bar' )->text()
		);
		$this->assertEquals(
			'You have foo (bar).',
			wfMessage( 'youhavenewmessages', [ 'foo', 'bar' ] )->text()
		);
	}

	/**
	 * @covers Message::exists
	 */
	public function testExists() {
		$this->assertTrue( wfMessage( 'mainpage' )->exists() );
		$this->assertTrue( wfMessage( 'mainpage' )->params( [] )->exists() );
		$this->assertTrue( wfMessage( 'mainpage' )->rawParams( 'foo', 123 )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->params( [] )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->rawParams( 'foo', 123 )->exists() );
	}

	/**
	 * @covers Message::__construct
	 * @covers Message::text
	 * @covers Message::plain
	 * @covers Message::escaped
	 * @covers Message::toString
	 */
	public function testToStringKey() {
		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->text() );
		$this->assertEquals( '<i-dont-exist-evar>', wfMessage( 'i-dont-exist-evar' )->text() );
		$this->assertEquals( '<i<dont>exist-evar>', wfMessage( 'i<dont>exist-evar' )->text() );
		$this->assertEquals( '<i-dont-exist-evar>', wfMessage( 'i-dont-exist-evar' )->plain() );
		$this->assertEquals( '<i<dont>exist-evar>', wfMessage( 'i<dont>exist-evar' )->plain() );
		$this->assertEquals( '&lt;i-dont-exist-evar&gt;', wfMessage( 'i-dont-exist-evar' )->escaped() );
		$this->assertEquals(
			'&lt;i&lt;dont&gt;exist-evar&gt;',
			wfMessage( 'i<dont>exist-evar' )->escaped()
		);
	}

	public static function provideToString() {
		return [
			[ 'mainpage', 'Main Page' ],
			[ 'i-dont-exist-evar', '<i-dont-exist-evar>' ],
			[ 'i-dont-exist-evar', '&lt;i-dont-exist-evar&gt;', 'escaped' ],
		];
	}

	/**
	 * @covers Message::toString
	 * @covers Message::__toString
	 * @dataProvider provideToString
	 */
	public function testToString( $key, $expect, $format = 'plain' ) {
		$msg = new Message( $key );
		$msg->$format();
		$this->assertEquals( $expect, $msg->toString() );
		$this->assertEquals( $expect, $msg->__toString() );
	}

	/**
	 * @covers Message::inLanguage
	 */
	public function testInLanguage() {
		$this->assertEquals( 'Main Page', wfMessage( 'mainpage' )->inLanguage( 'en' )->text() );
		$this->assertEquals( 'Заглавная страница',
			wfMessage( 'mainpage' )->inLanguage( 'ru' )->text() );

		// NOTE: make sure internal caching of the message text is reset appropriately
		$msg = wfMessage( 'mainpage' );
		$this->assertEquals( 'Main Page', $msg->inLanguage( Language::factory( 'en' ) )->text() );
		$this->assertEquals(
			'Заглавная страница',
			$msg->inLanguage( Language::factory( 'ru' ) )->text()
		);
	}

	/**
	 * @covers Message::rawParam
	 * @covers Message::rawParams
	 */
	public function testRawParams() {
		$this->assertEquals(
			'(Заглавная страница)',
			wfMessage( 'parentheses', 'Заглавная страница' )->plain()
		);
		$this->assertEquals(
			'(Заглавная страница $1)',
			wfMessage( 'parentheses', 'Заглавная страница $1' )->plain()
		);
		$this->assertEquals(
			'(Заглавная страница)',
			wfMessage( 'parentheses' )->rawParams( 'Заглавная страница' )->plain()
		);
		$this->assertEquals(
			'(Заглавная страница $1)',
			wfMessage( 'parentheses' )->rawParams( 'Заглавная страница $1' )->plain()
		);
	}

	/**
	 * @covers RawMessage::__construct
	 * @covers RawMessage::fetchMessage
	 */
	public function testRawMessage() {
		$msg = new RawMessage( 'example &' );
		$this->assertEquals( 'example &', $msg->plain() );
		$this->assertEquals( 'example &amp;', $msg->escaped() );
	}

	/**
	 * @covers Message::params
	 * @covers Message::toString
	 * @covers Message::replaceParameters
	 */
	public function testReplaceManyParams() {
		$msg = new RawMessage( '$1$2$3$4$5$6$7$8$9$10$11$12' );
		// One less than above has placeholders
		$params = [ 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k' ];
		$this->assertEquals(
			'abcdefghijka2',
			$msg->params( $params )->plain(),
			'Params > 9 are replaced correctly'
		);

		$msg = new RawMessage( 'Params$*' );
		$params = [ 'ab', 'bc', 'cd' ];
		$this->assertEquals(
			'Params: ab, bc, cd',
			$msg->params( $params )->text()
		);
	}

	/**
	 * @covers Message::numParam
	 * @covers Message::numParams
	 */
	public function testNumParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatNum( 123456.789 ),
			$msg->inLanguage( $lang )->numParams( 123456.789 )->plain(),
			'numParams is handled correctly'
		);
	}

	/**
	 * @covers Message::durationParam
	 * @covers Message::durationParams
	 */
	public function testDurationParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatDuration( 1234 ),
			$msg->inLanguage( $lang )->durationParams( 1234 )->plain(),
			'durationParams is handled correctly'
		);
	}

	/**
	 * FIXME: This should not need database, but Language#formatExpiry does (bug 55912)
	 * @group Database
	 * @covers Message::expiryParam
	 * @covers Message::expiryParams
	 */
	public function testExpiryParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatExpiry( wfTimestampNow() ),
			$msg->inLanguage( $lang )->expiryParams( wfTimestampNow() )->plain(),
			'expiryParams is handled correctly'
		);
	}

	/**
	 * @covers Message::timeperiodParam
	 * @covers Message::timeperiodParams
	 */
	public function testTimeperiodParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatTimePeriod( 1234 ),
			$msg->inLanguage( $lang )->timeperiodParams( 1234 )->plain(),
			'timeperiodParams is handled correctly'
		);
	}

	/**
	 * @covers Message::sizeParam
	 * @covers Message::sizeParams
	 */
	public function testSizeParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatSize( 123456 ),
			$msg->inLanguage( $lang )->sizeParams( 123456 )->plain(),
			'sizeParams is handled correctly'
		);
	}

	/**
	 * @covers Message::bitrateParam
	 * @covers Message::bitrateParams
	 */
	public function testBitrateParams() {
		$lang = Language::factory( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertEquals(
			$lang->formatBitrate( 123456 ),
			$msg->inLanguage( $lang )->bitrateParams( 123456 )->plain(),
			'bitrateParams is handled correctly'
		);
	}

	public static function providePlaintextParams() {
		return [
			[
				'one $2 <div>foo</div> [[Bar]] {{Baz}} &lt;',
				'plain',
			],

			[
				// expect
				'one $2 <div>foo</div> [[Bar]] {{Baz}} &lt;',
				// format
				'text',
			],
			[
				'one $2 &lt;div&gt;foo&lt;/div&gt; [[Bar]] {{Baz}} &amp;lt;',
				'escaped',
			],

			[
				'one $2 &lt;div&gt;foo&lt;/div&gt; [[Bar]] {{Baz}} &amp;lt;',
				'parse',
			],

			[
				"<p>one $2 &lt;div&gt;foo&lt;/div&gt; [[Bar]] {{Baz}} &amp;lt;\n</p>",
				'parseAsBlock',
			],
		];
	}

	/**
	 * @covers Message::plaintextParam
	 * @covers Message::plaintextParams
	 * @covers Message::formatPlaintext
	 * @covers Message::toString
	 * @covers Message::parse
	 * @covers Message::parseAsBlock
	 * @dataProvider providePlaintextParams
	 */
	public function testPlaintextParams( $expect, $format ) {
		$lang = Language::factory( 'en' );

		$msg = new RawMessage( '$1 $2' );
		$params = [
			'one $2',
			'<div>foo</div> [[Bar]] {{Baz}} &lt;',
		];
		$this->assertEquals(
			$expect,
			$msg->inLanguage( $lang )->plaintextParams( $params )->$format(),
			"Fail formatting for $format"
		);
	}

	public static function provideParser() {
		return [
			[
				"''&'' <x><!-- x -->",
				'plain',
			],

			[
				"''&'' <x><!-- x -->",
				'text',
			],
			[
				'<i>&amp;</i> &lt;x&gt;',
				'parse',
			],

			[
				"<p><i>&amp;</i> &lt;x&gt;\n</p>",
				'parseAsBlock',
			],
		];
	}

	/**
	 * @covers Message::text
	 * @covers Message::parse
	 * @covers Message::parseAsBlock
	 * @covers Message::toString
	 * @covers Message::transformText
	 * @covers Message::parseText
	 * @dataProvider provideParser
	 */
	public function testParser( $expect, $format ) {
		$msg = new RawMessage( "''&'' <x><!-- x -->" );
		$this->assertEquals(
			$expect,
			$msg->inLanguage( 'en' )->$format()
		);
	}

	/**
	 * @covers Message::inContentLanguage
	 */
	public function testInContentLanguage() {
		$this->setUserLang( 'fr' );

		// NOTE: make sure internal caching of the message text is reset appropriately
		$msg = wfMessage( 'mainpage' );
		$this->assertEquals( 'Hauptseite', $msg->inLanguage( 'de' )->plain(), "inLanguage( 'de' )" );
		$this->assertEquals( 'Main Page', $msg->inContentLanguage()->plain(), "inContentLanguage()" );
		$this->assertEquals( 'Accueil', $msg->inLanguage( 'fr' )->plain(), "inLanguage( 'fr' )" );
	}

	/**
	 * @covers Message::inContentLanguage
	 */
	public function testInContentLanguageOverride() {
		$this->setMwGlobals( [
			'wgForceUIMsgAsContentMsg' => [ 'mainpage' ],
		] );
		$this->setUserLang( 'fr' );

		// NOTE: make sure internal caching of the message text is reset appropriately.
		// NOTE: wgForceUIMsgAsContentMsg forces the messages *current* language to be used.
		$msg = wfMessage( 'mainpage' );
		$this->assertEquals(
			'Accueil',
			$msg->inContentLanguage()->plain(),
			'inContentLanguage() with ForceUIMsg override enabled'
		);
		$this->assertEquals( 'Main Page', $msg->inLanguage( 'en' )->plain(), "inLanguage( 'en' )" );
		$this->assertEquals(
			'Main Page',
			$msg->inContentLanguage()->plain(),
			'inContentLanguage() with ForceUIMsg override enabled'
		);
		$this->assertEquals( 'Hauptseite', $msg->inLanguage( 'de' )->plain(), "inLanguage( 'de' )" );
	}

	/**
	 * @expectedException MWException
	 * @covers Message::inLanguage
	 */
	public function testInLanguageThrows() {
		wfMessage( 'foo' )->inLanguage( 123 );
	}

	/**
	 * @covers Message::serialize
	 * @covers Message::unserialize
	 */
	public function testSerialization() {
		$msg = new Message( 'parentheses' );
		$msg->rawParams( '<a>foo</a>' );
		$msg->title( Title::newFromText( 'Testing' ) );
		$this->assertEquals( '(<a>foo</a>)', $msg->parse(), 'Sanity check' );
		$msg = unserialize( serialize( $msg ) );
		$this->assertEquals( '(<a>foo</a>)', $msg->parse() );
		$title = TestingAccessWrapper::newFromObject( $msg )->title;
		$this->assertInstanceOf( 'Title', $title );
		$this->assertEquals( 'Testing', $title->getFullText() );

		$msg = new Message( 'mainpage' );
		$msg->inLanguage( 'de' );
		$this->assertEquals( 'Hauptseite', $msg->plain(), 'Sanity check' );
		$msg = unserialize( serialize( $msg ) );
		$this->assertEquals( 'Hauptseite', $msg->plain() );
	}

	/**
	 * @covers Message::newFromSpecifier
	 * @dataProvider provideNewFromSpecifier
	 */
	public function testNewFromSpecifier( $value, $expectedText ) {
		$message = Message::newFromSpecifier( $value );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertSame( $expectedText, $message->text() );
	}

	public function provideNewFromSpecifier() {
		$messageSpecifier = $this->getMockForAbstractClass( MessageSpecifier::class );
		$messageSpecifier->expects( $this->any() )->method( 'getKey' )->willReturn( 'mainpage' );
		$messageSpecifier->expects( $this->any() )->method( 'getParams' )->willReturn( [] );

		return [
			'string' => [ 'mainpage', 'Main Page' ],
			'array' => [ [ 'youhavenewmessages', 'foo', 'bar' ], 'You have foo (bar).' ],
			'Message' => [ new Message( 'youhavenewmessages', [ 'foo', 'bar' ] ), 'You have foo (bar).' ],
			'RawMessage' => [ new RawMessage( 'foo ($1)', [ 'bar' ] ), 'foo (bar)' ],
			'MessageSpecifier' => [ $messageSpecifier, 'Main Page' ],
			'nested RawMessage' => [ [ new RawMessage( 'foo ($1)', [ 'bar' ] ) ], 'foo (bar)' ],
		];
	}
}

