<?php

use MediaWiki\Api\ApiMessage;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageReferenceValue;
use Wikimedia\Assert\ParameterTypeException;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Message\MessageSpecifier;

/**
 * @group Language
 * @group Database
 * @covers ::wfMessage
 * @covers \MediaWiki\Message\Message
 */
class MessageTest extends MediaWikiLangTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ForceUIMsgAsContentMsg, [] );
		$this->setUserLang( 'en' );
	}

	/**
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $expectedLang, $key, $params, $language ) {
		$message = new Message( $key, $params, $language );

		$this->assertSame( $key, $message->getKey() );
		$this->assertSame( $params, $message->getParams() );
		$this->assertSame( $expectedLang->getCode(), $message->getLanguage()->getCode() );

		$messageSpecifier = $this->getMockForAbstractClass( MessageSpecifier::class );
		$messageSpecifier->method( 'getKey' )->willReturn( $key );
		$messageSpecifier->method( 'getParams' )->willReturn( $params );
		$message = new Message( $messageSpecifier, [], $language );

		$this->assertSame( $key, $message->getKey() );
		$this->assertSame( $params, $message->getParams() );
		$this->assertSame( $expectedLang->getCode(), $message->getLanguage()->getCode() );
	}

	public static function provideConstructor() {
		$langDe = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'de' );
		$langEn = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' );

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
				[],
				[ [] ],
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
				[ Message::rawParam( 'baz' ) ],
				[ Message::rawParam( 'baz' ) ],
			],
			[
				[ Message::rawParam( 'baz' ), 'foo' ],
				[ Message::rawParam( 'baz' ), 'foo' ],
			],
			[
				[ Message::rawParam( 'baz' ) ],
				[ [ Message::rawParam( 'baz' ) ] ],
			],
			[
				[ Message::rawParam( 'baz' ), 'foo' ],
				[ [ Message::rawParam( 'baz' ), 'foo' ] ],
			],

			// Test handling of erroneous input, to detect if it changes
			[
				[ [ 'baz', 'foo' ], 'hhh' ],
				[ [ 'baz', 'foo' ], 'hhh' ],
			],
			[
				[ [ 'baz', 'foo' ], 'hhh', [ 'ahahahahha' ] ],
				[ [ 'baz', 'foo' ], 'hhh', [ 'ahahahahha' ] ],
			],
			[
				[ [ 'baz', 'foo' ], [ 'ahahahahha' ] ],
				[ [ 'baz', 'foo' ], [ 'ahahahahha' ] ],
			],
			[
				[ [ 'baz' ], [ 'ahahahahha' ] ],
				[ [ 'baz' ], [ 'ahahahahha' ] ],
			],
		];
	}

	/**
	 * @dataProvider provideConstructorParams
	 */
	public function testConstructorParams( $expected, $args ) {
		$msg = new Message( 'imasomething' );

		$returned = $msg->params( ...$args );

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
	 * @dataProvider provideConstructorLanguage
	 */
	public function testConstructorLanguage( $key, $params, $languageCode ) {
		$language = $this->getServiceContainer()->getLanguageFactory()
			->getLanguage( $languageCode );
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
				'exception' => InvalidArgumentException::class,
			],
			'null' => [
				'key' => null,
				'expected' => null,
				'exception' => InvalidArgumentException::class,
			],
			'bad type' => [
				'key' => 123,
				'expected' => null,
				'exception' => InvalidArgumentException::class,
			],
		];
	}

	/**
	 * @dataProvider provideKeys
	 */
	public function testKeys( $key, $expected, $exception = null ) {
		if ( $exception ) {
			$this->expectException( $exception );
		}

		$msg = new Message( $key );
		$this->assertContains( $msg->getKey(), $expected );
		$this->assertSame( $expected, $msg->getKeysToTry() );
		$this->assertSame( count( $expected ) > 1, $msg->isMultiKey() );
	}

	public function testWfMessage() {
		$this->assertInstanceOf( Message::class, wfMessage( 'mainpage' ) );
		$this->assertInstanceOf( Message::class, wfMessage( 'i-dont-exist-evar' ) );
	}

	public function testNewFromKey() {
		$this->assertInstanceOf( Message::class, Message::newFromKey( 'mainpage' ) );
		$this->assertInstanceOf( Message::class, Message::newFromKey( 'i-dont-exist-evar' ) );
	}

	public function testWfMessageParams() {
		$this->assertSame( 'Return to $1.', wfMessage( 'returnto' )->text() );
		$this->assertSame( 'Return to $1.', wfMessage( 'returnto', [] )->text() );
		$this->assertSame(
			'Return to 1,024.',
			wfMessage( 'returnto', Message::numParam( 1024 ) )->text()
		);
		$this->assertSame(
			'Return to 1,024.',
			wfMessage( 'returnto', [ Message::numParam( 1024 ) ] )->text()
		);
		$this->assertSame(
			'You have foo (bar).',
			wfMessage( 'new-messages', 'foo', 'bar' )->text()
		);
		$this->assertSame(
			'You have foo (bar).',
			wfMessage( 'new-messages', [ 'foo', 'bar' ] )->text()
		);
		$this->assertSame(
			'You have 1,024 (bar).',
			wfMessage(
				'new-messages',
				Message::numParam( 1024 ), 'bar'
			)->text()
		);
		$this->assertSame(
			'You have foo (2,048).',
			wfMessage(
				'new-messages',
				'foo', Message::numParam( 2048 )
			)->text()
		);
		$this->assertSame(
			'You have 1,024 (2,048).',
			wfMessage(
				'new-messages',
				[ Message::numParam( 1024 ), Message::numParam( 2048 ) ]
			)->text()
		);
	}

	public function testExists() {
		$this->assertTrue( wfMessage( 'mainpage' )->exists() );
		$this->assertTrue( wfMessage( 'mainpage' )->params( [] )->exists() );
		$this->assertTrue( wfMessage( 'mainpage' )->rawParams( 'foo', 123 )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->params( [] )->exists() );
		$this->assertFalse( wfMessage( 'i-dont-exist-evar' )->rawParams( 'foo', 123 )->exists() );
	}

	public function testToStringKey() {
		$this->assertSame( 'Main Page', wfMessage( 'mainpage' )->text() );
		$this->assertSame( '⧼i-dont-exist-evar⧽', wfMessage( 'i-dont-exist-evar' )->text() );
		$this->assertSame( '⧼i&lt;dont&gt;exist-evar⧽', wfMessage( 'i<dont>exist-evar' )->text() );
		$this->assertSame( '⧼i-dont-exist-evar⧽', wfMessage( 'i-dont-exist-evar' )->plain() );
		$this->assertSame( '⧼i&lt;dont&gt;exist-evar⧽', wfMessage( 'i<dont>exist-evar' )->plain() );
		$this->assertSame( '⧼i-dont-exist-evar⧽', wfMessage( 'i-dont-exist-evar' )->escaped() );
		$this->assertSame(
			'⧼i&lt;dont&gt;exist-evar⧽',
			wfMessage( 'i<dont>exist-evar' )->escaped()
		);
	}

	public static function provideToString() {
		return [
			// key, transformation, transformed, transformed implicitly
			[ 'mainpage', 'plain', 'Main Page', 'Main Page' ],
			[ 'i-dont-exist-evar', 'plain', '⧼i-dont-exist-evar⧽', '⧼i-dont-exist-evar⧽' ],
			[ 'i-dont-exist-evar', 'escaped', '⧼i-dont-exist-evar⧽', '⧼i-dont-exist-evar⧽' ],
			[ 'script>alert(1)</script', 'escaped', '⧼script&gt;alert(1)&lt;/script⧽',
				'⧼script&gt;alert(1)&lt;/script⧽' ],
			[ 'script>alert(1)</script', 'plain', '⧼script&gt;alert(1)&lt;/script⧽',
				'⧼script&gt;alert(1)&lt;/script⧽' ],
		];
	}

	/**
	 * @dataProvider provideToString
	 */
	public function testToString( $key, $format, $expect, $expectImplicit ) {
		$msg = new Message( $key );
		$this->assertSame( $expect, $msg->$format() );

		// This used to behave the same as toString() and was a security risk.
		// It now has a stable return value that is always parsed/sanitized. (T146416)
		$this->assertSame( $expectImplicit, $msg->__toString(), '__toString is not affected by format call' );
	}

	public static function provideToString_raw() {
		return [
			[ '<span>foo</span>', 'parse', '<span>foo</span>', '<span>foo</span>' ],
			[ '<span>foo</span>', 'escaped', '&lt;span&gt;foo&lt;/span&gt;',
				'<span>foo</span>' ],
			[ '<span>foo</span>', 'plain', '<span>foo</span>', '<span>foo</span>' ],
			[ '<script>alert(1)</script>', 'parse', '&lt;script&gt;alert(1)&lt;/script&gt;',
				'&lt;script&gt;alert(1)&lt;/script&gt;' ],
			[ '<script>alert(1)</script>', 'escaped', '&lt;script&gt;alert(1)&lt;/script&gt;',
				'&lt;script&gt;alert(1)&lt;/script&gt;' ],
			[ '<script>alert(1)</script>', 'plain', '<script>alert(1)</script>',
				'&lt;script&gt;alert(1)&lt;/script&gt;' ],
		];
	}

	/**
	 * @dataProvider provideToString_raw
	 */
	public function testToString_raw( $message, $format, $expect, $expectImplicit ) {
		// make the message behave like RawMessage and use the key as-is
		$msg = $this->getMockBuilder( Message::class )->onlyMethods( [ 'fetchMessage' ] )
			->disableOriginalConstructor()
			->getMock();
		$msg->method( 'fetchMessage' )->willReturn( $message );
		/** @var Message $msg */

		$this->assertSame( $expect, $msg->$format() );

		$this->assertSame( $expectImplicit, $msg->__toString() );
	}

	public function testInLanguage() {
		$this->assertSame( 'Main Page', wfMessage( 'mainpage' )->inLanguage( 'en' )->text() );
		$this->assertSame( 'Главна страна',
			wfMessage( 'mainpage' )->inLanguage( 'sr-ec' )->text() );

		// NOTE: make sure internal caching of the message text is reset appropriately
		$msg = wfMessage( 'mainpage' );
		$this->assertSame( 'Main Page', $msg->inLanguage( 'en' )->text() );
		$this->assertSame(
			'Главна страна',
			$msg->inLanguage( 'sr-ec' )->text()
		);
	}

	public function testInLanguageBcp47() {
		$en = new Bcp47CodeValue( 'en' );
		$sr = new Bcp47CodeValue( 'sr-Cyrl' );
		$this->assertSame( 'Main Page', wfMessage( 'mainpage' )->inLanguage( $en )->text() );
		$this->assertSame( 'Главна страна',
			wfMessage( 'mainpage' )->inLanguage( $sr )->text() );

		// NOTE: make sure internal caching of the message text is reset appropriately
		$msg = wfMessage( 'mainpage' );
		$this->assertSame( 'Main Page', $msg->inLanguage( $en )->text() );
		$this->assertSame(
			'Главна страна',
			$msg->inLanguage( $sr )->text()
		);
	}

	public function testRawParams() {
		$this->assertSame(
			'(Заглавная страница)',
			wfMessage( 'parentheses', 'Заглавная страница' )->plain()
		);
		$this->assertSame(
			'(Заглавная страница $1)',
			wfMessage( 'parentheses', 'Заглавная страница $1' )->plain()
		);
		$this->assertSame(
			'(Заглавная страница)',
			wfMessage( 'parentheses' )->rawParams( 'Заглавная страница' )->plain()
		);
		$this->assertSame(
			'(Заглавная страница $1)',
			wfMessage( 'parentheses' )->rawParams( 'Заглавная страница $1' )->plain()
		);
	}

	/**
	 * @covers \MediaWiki\Language\RawMessage
	 */
	public function testRawMessage() {
		$msg = new RawMessage( 'example &' );
		$this->assertSame( 'example &', $msg->plain() );
		$this->assertSame( 'example &amp;', $msg->escaped() );
	}

	public static function provideRawMessage() {
		yield 'No params' => [
			new RawMessage( 'Foo Bar' ),
			'Foo Bar',
		];
		yield 'Single param' => [
			new RawMessage( '$1', [ 'Foo Bar' ] ),
			'Foo Bar',
		];
		yield 'Multiple params' => [
			new RawMessage( '$2 and $1', [ 'One', 'Two' ] ),
			'Two and One',
		];
	}

	/**
	 * @dataProvider provideRawMessage
	 * @covers \MediaWiki\Language\RawMessage
	 */
	public function testRawMessageParams( RawMessage $m, string $param ) {
		$this->assertEquals( [ $param ], $m->getParams() );
	}

	/**
	 * @dataProvider provideRawMessage
	 * @covers \MediaWiki\Language\RawMessage
	 */
	public function testRawMessageDisassembleSpecifier( RawMessage $m, string $text ) {
		// Check this just in case, although it's not really covered by this test.
		$this->assertEquals( $text, $m->text(), 'output from RawMessage itself' );
		// Verify that RawMessage can be used as a MessageSpecifier, producing the same output.
		$msg = wfMessage( $m );
		$this->assertEquals( $text, $msg->text(), 'output from RawMessage used as MessageSpecifier' );
		// Verify that if you disassemble it using MessageSpecifier's getKey() and getParams() methods,
		// then assemble a new MessageSpecifier using the return values, you will get the same output.
		$msg2 = wfMessage( $m->getKey(), ...$m->getParams() );
		$this->assertEquals( $text, $msg2->text(), 'output from RawMessage disassembled' );
	}

	/**
	 * @covers \MediaWiki\Language\RawMessage
	 * @covers \MediaWiki\Parser\CoreTagHooks::html
	 */
	public function testRawHtmlInMsg() {
		$this->overrideConfigValue( MainConfigNames::RawHtml, true );

		$msg = new RawMessage( '<html><script>alert("xss")</script></html>' );
		$txt = '<span class="error">&lt;html&gt; tags cannot be' .
			' used outside of normal pages.</span>';
		$this->assertSame( $txt, $msg->parse() );
	}

	public function testReplaceManyParams() {
		$msg = new RawMessage( '$1$2$3$4$5$6$7$8$9$10$11$12' );
		// One less than above has placeholders
		$params = [ 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k' ];
		$this->assertSame(
			'abcdefghijka2',
			$msg->params( $params )->plain(),
			'Params > 9 are replaced correctly'
		);
	}

	public function testNumParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertSame(
			$lang->formatNum( 123456.789 ),
			$msg->inLanguage( $lang )->numParams( 123456.789 )->plain(),
			'numParams is handled correctly'
		);
	}

	public function testDurationParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertSame(
			$lang->formatDuration( 1234 ),
			$msg->inLanguage( $lang )->durationParams( 1234 )->plain(),
			'durationParams is handled correctly'
		);
	}

	/**
	 * FIXME: This should not need database, but Language#formatExpiry does (T57912)
	 */
	public function testExpiryParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$msg = new RawMessage( '$1' );

		$ts = wfTimestampNow();
		$this->assertSame(
			$lang->formatExpiry( $ts ),
			$msg->inLanguage( $lang )->expiryParams( $ts )->plain(),
			'expiryParams is handled correctly'
		);
	}

	public function testDateTimeParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$msg = new RawMessage( '$1' );

		$ts = wfTimestampNow();
		$this->assertSame(
			$lang->timeanddate( $ts ),
			$msg->inLanguage( $lang )->dateTimeParams( $ts )->plain(),
			'dateTime is handled correctly'
		);
	}

	public function testDateParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$msg = new RawMessage( '$1' );

		$ts = wfTimestampNow();
		$this->assertSame(
			$lang->date( $ts ),
			$msg->inLanguage( $lang )->dateParams( $ts )->plain(),
			'date is handled correctly'
		);
	}

	public function testTimeParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$msg = new RawMessage( '$1' );

		$ts = wfTimestampNow();
		$this->assertSame(
			$lang->time( $ts ),
			$msg->inLanguage( $lang )->timeParams( $ts )->plain(),
			'time is handled correctly'
		);
	}

	public function testUserGroupParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'qqx' );
		$msg = new RawMessage( '$1' );
		$this->setUserLang( $lang );
		$this->assertSame(
			'(group-bot)',
			$msg->userGroupParams( 'bot' )->plain(),
			'user group is handled correctly'
		);
	}

	public function testTimeperiodParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertSame(
			$lang->formatTimePeriod( 1234 ),
			$msg->inLanguage( $lang )->timeperiodParams( 1234 )->plain(),
			'timeperiodParams is handled correctly'
		);
	}

	public function testSizeParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertSame(
			$lang->formatSize( 123456 ),
			$msg->inLanguage( $lang )->sizeParams( 123456 )->plain(),
			'sizeParams is handled correctly'
		);
	}

	public function testBitrateParams() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$msg = new RawMessage( '$1' );

		$this->assertSame(
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
	 * @dataProvider providePlaintextParams
	 */
	public function testPlaintextParams( $expect, $format ) {
		$msg = new RawMessage( '$1 $2' );
		$params = [
			'one $2',
			'<div>foo</div> [[Bar]] {{Baz}} &lt;',
		];
		$this->assertSame(
			$expect,
			$msg->inLanguage( 'en' )->plaintextParams( $params )->$format(),
			"Fail formatting for $format"
		);
	}

	public static function provideListParam() {
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'de' );
		$msg1 = new Message( 'mainpage', [], $lang );
		$msg2 = new RawMessage( "''link''", [], $lang );

		return [
			'Simple comma list' => [
				[ 'a', 'b', 'c' ],
				'comma',
				'text',
				'a, b, c'
			],

			'Simple semicolon list' => [
				[ 'a', 'b', 'c' ],
				'semicolon',
				'text',
				'a; b; c'
			],

			'Simple pipe list' => [
				[ 'a', 'b', 'c' ],
				'pipe',
				'text',
				'a | b | c'
			],

			'Simple text list' => [
				[ 'a', 'b', 'c' ],
				'text',
				'text',
				'a, b and c'
			],

			'Empty list' => [
				[],
				'comma',
				'text',
				''
			],

			'List with all "before" params, ->text()' => [
				[ "''link''", Message::numParam( 12345678 ) ],
				'semicolon',
				'text',
				'\'\'link\'\'; 12,345,678'
			],

			'List with all "before" params, ->parse()' => [
				[ "''link''", Message::numParam( 12345678 ) ],
				'semicolon',
				'parse',
				'<i>link</i>; 12,345,678'
			],

			'List with all "after" params, ->text()' => [
				[ $msg1, $msg2, Message::rawParam( '[[foo]]' ) ],
				'semicolon',
				'text',
				'Main Page; \'\'link\'\'; [[foo]]'
			],

			'List with all "after" params, ->parse()' => [
				[ $msg1, $msg2, Message::rawParam( '[[foo]]' ) ],
				'semicolon',
				'parse',
				'Main Page; <i>link</i>; [[foo]]'
			],

			'List with both "before" and "after" params, ->text()' => [
				[ $msg1, $msg2, Message::rawParam( '[[foo]]' ), "''link''", Message::numParam( 12345678 ) ],
				'semicolon',
				'text',
				'Main Page; \'\'link\'\'; [[foo]]; \'\'link\'\'; 12,345,678'
			],

			'List with both "before" and "after" params, ->parse()' => [
				[ $msg1, $msg2, Message::rawParam( '[[foo]]' ), "''link''", Message::numParam( 12345678 ) ],
				'semicolon',
				'parse',
				'Main Page; <i>link</i>; [[foo]]; <i>link</i>; 12,345,678'
			],
		];
	}

	/**
	 * @dataProvider provideListParam
	 */
	public function testListParam( $list, $type, $format, $expect ) {
		$msg = new RawMessage( '$1' );
		$msg->params( [ Message::listParam( $list, $type ) ] );
		$this->assertEquals(
			$expect,
			$msg->inLanguage( 'en' )->$format()
		);
	}

	public function testMessageAsParam() {
		$msg = new Message( 'returnto', [
			new Message( 'apihelp-link', [
				'foo', new Message( 'mainpage', [],
					$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' ) )
			], $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'de' ) )
		], $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'es' ) );

		$this->assertEquals(
			'Volver a [[Special:ApiHelp/foo|Página principal]].',
			$msg->text(),
			'Process with ->text()'
		);
		$this->assertEquals(
			'<p>Volver a <a href="/wiki/Special:ApiHelp/foo" title="Special:ApiHelp/foo">Página '
				. "principal</a>.\n</p>",
			$msg->parseAsBlock(),
			'Process with ->parseAsBlock()'
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
	 * @dataProvider provideParser
	 */
	public function testParser( $expect, $format ) {
		$msg = new RawMessage( "''&'' <x><!-- x -->" );
		$this->assertSame(
			$expect,
			$msg->inLanguage( 'en' )->$format()
		);
	}

	/**
	 * @covers \LanguageQqx
	 */
	public function testQqxPlaceholders() {
		$this->assertSame(
			'(test)',
			wfMessage( 'test' )->inLanguage( 'qqx' )->text()
		);
		$this->assertSame(
			'(test: a, b)',
			wfMessage( 'test' )->params( 'a', 'b' )->inLanguage( 'qqx' )->text()
		);
		$this->assertSame(
			'(test / other-test)',
			wfMessageFallback( 'test', 'other-test' )->inLanguage( 'qqx' )->text()
		);
		$this->assertSame(
			'(test / other-test: a, b)',
			wfMessageFallback( 'test', 'other-test' )->params( 'a', 'b' )->inLanguage( 'qqx' )->text()
		);
	}

	public function testInContentLanguage() {
		$this->setUserLang( 'fr' );

		// NOTE: make sure internal caching of the message text is reset appropriately
		$msg = wfMessage( 'mainpage' );
		$this->assertSame( 'Hauptseite', $msg->inLanguage( 'de' )->plain(), "inLanguage( 'de' )" );
		$this->assertSame( 'Main Page', $msg->inContentLanguage()->plain(), "inContentLanguage()" );
		$this->assertSame( 'Accueil', $msg->inLanguage( 'fr' )->plain(), "inLanguage( 'fr' )" );
	}

	public function testInContentLanguageOverride() {
		$this->overrideConfigValue( MainConfigNames::ForceUIMsgAsContentMsg, [ 'mainpage' ] );
		$this->setUserLang( 'fr' );

		// NOTE: make sure internal caching of the message text is reset appropriately.
		// NOTE: wgForceUIMsgAsContentMsg forces the messages *current* language to be used.
		$msg = wfMessage( 'mainpage' );
		$this->assertSame(
			'Accueil',
			$msg->inContentLanguage()->plain(),
			'inContentLanguage() with ForceUIMsg override enabled'
		);
		$this->assertSame( 'Main Page', $msg->inLanguage( 'en' )->plain(), "inLanguage( 'en' )" );
		$this->assertSame(
			'Main Page',
			$msg->inContentLanguage()->plain(),
			'inContentLanguage() with ForceUIMsg override enabled'
		);
		$this->assertSame( 'Hauptseite', $msg->inLanguage( 'de' )->plain(), "inLanguage( 'de' )" );
	}

	public static function provideInLanguageValid() {
		yield 'en' => [ 'en', 'en' ];
		yield 'variant lower' => [ 'en-gb', 'en-gb' ];
		yield 'variant upper' => [ 'en-GB', 'en-GB' ];
		yield 'variant fake' => [ 'en-FloP', 'en-FloP' ];
		yield 'deprecated' => [ 'be-x-old', 'be-tarask' ];
		yield 'weird fake' => [ 'k@B!M', 'k@B!M' ];
		yield 'long fake' => [ str_repeat( 'x', 100 ), str_repeat( 'x', 100 ) ];
	}

	/**
	 * @dataProvider provideInLanguageValid
	 */
	public function testInLanguageValid( $langCode, $expected ) {
		$this->assertSame(
			$expected,
			wfMessage( 'foo' )->inLanguage( $langCode )->getLanguageCode()
		);
	}

	public static function provideInLanguageInvalid() {
		yield 'invalid character' => [ 'qqx&1<' ];
		yield 'too long' => [ str_repeat( 'x', 200 ) ];
	}

	/**
	 * @dataProvider provideInLanguageInvalid
	 */
	public function testInLanguageInvalid( $langCode ) {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid language code' );
		wfMessage( 'foo' )->inLanguage( $langCode );
	}

	public function testInLanguageType() {
		$this->expectException( ParameterTypeException::class );
		wfMessage( 'foo' )->inLanguage( 123 );
	}

	/**
	 * @dataProvider provideSerializationRoundtrip
	 */
	public function testSerialization( $msgFunc, $serialized, $parsed ) {
		$msg = $msgFunc();
		$this->assertSame( $serialized, serialize( $msg ) );
		$this->assertSame( $parsed, $msg->parse() );
	}

	/**
	 * @dataProvider provideSerializationRoundtrip
	 * @dataProvider provideSerializationLegacy
	 */
	public function testUnserialization( $msgFunc, $serialized, $parsed ) {
		$msg = $msgFunc();
		$this->assertEquals( $msg, unserialize( $serialized ) );
		$this->assertSame( $parsed, unserialize( $serialized )->parse() );
	}

	public static function provideSerializationRoundtrip() {
		// Test cases where we can test both serialization and unserialization.
		// These really ought to use the MessageSerializationTestTrait, but
		// doing so is complicated (T373719).

		yield "Serializing raw parameters" => [
			static fn () => ( new Message( 'parentheses' ) )->rawParams( '<a>foo</a>' ),
			'O:25:"MediaWiki\Message\Message":7:{s:9:"interface";b:1;s:8:"language";N;s:3:"key";s:11:"parentheses";s:9:"keysToTry";a:1:{i:0;s:11:"parentheses";}s:10:"parameters";a:1:{i:0;O:29:"Wikimedia\Message\ScalarParam":2:{s:7:"' . chr( 0 ) . '*' . chr( 0 ) . 'type";s:3:"raw";s:8:"' . chr( 0 ) . '*' . chr( 0 ) . 'value";s:10:"<a>foo</a>";}}s:11:"useDatabase";b:1;s:10:"titlevalue";N;}',
			'(<a>foo</a>)',
		];

		yield "Serializing message with a context page" => [
			static fn () => ( new Message( 'rawmessage', [ '{{PAGENAME}}' ] ) )
				->page( PageReferenceValue::localReference( NS_MAIN, 'Testing' ) ),
			'O:25:"MediaWiki\Message\Message":7:{s:9:"interface";b:1;s:8:"language";N;s:3:"key";s:10:"rawmessage";s:9:"keysToTry";a:1:{i:0;s:10:"rawmessage";}s:10:"parameters";a:1:{i:0;s:12:"{{PAGENAME}}";}s:11:"useDatabase";b:1;s:10:"titlevalue";a:2:{i:0;i:0;i:1;s:7:"Testing";}}',
			'Testing',
		];

		yield "Serializing language" => [
			static fn () => ( new Message( 'mainpage' ) )->inLanguage( 'de' ),
			'O:25:"MediaWiki\Message\Message":7:{s:9:"interface";b:0;s:8:"language";s:2:"de";s:3:"key";s:8:"mainpage";s:9:"keysToTry";a:1:{i:0;s:8:"mainpage";}s:10:"parameters";a:0:{}s:11:"useDatabase";b:1;s:10:"titlevalue";N;}',
			'Hauptseite',
		];
	}

	public static function provideSerializationLegacy() {
		// Test cases where we can test only unserialization, because the serialization format changed.

		yield "MW 1.42: Magic arrays instead of MessageParam objects" => [
			static fn () => ( new Message( 'parentheses' ) )->rawParams( '<a>foo</a>' ),
			'O:25:"MediaWiki\Message\Message":7:{s:9:"interface";b:1;s:8:"language";N;s:3:"key";s:11:"parentheses";s:9:"keysToTry";a:1:{i:0;s:11:"parentheses";}s:10:"parameters";a:1:{i:0;a:1:{s:3:"raw";s:10:"<a>foo</a>";}}s:11:"useDatabase";b:1;s:10:"titlevalue";N;}',
			'(<a>foo</a>)',
		];

		yield "MW 1.41: Un-namespaced class" => [
			static fn () => new Message( 'mainpage' ),
			'O:7:"Message":7:{s:9:"interface";b:1;s:8:"language";N;s:3:"key";s:8:"mainpage";s:9:"keysToTry";a:1:{i:0;s:8:"mainpage";}s:10:"parameters";a:0:{}s:11:"useDatabase";b:1;s:10:"titlevalue";N;}',
			'Main Page',
		];

		yield "MW 1.34: 'titlestr' instead of 'titlevalue'" => [
			static fn () => ( new Message( 'rawmessage', [ '{{PAGENAME}}' ] ) )
				->page( PageReferenceValue::localReference( NS_MAIN, 'Testing' ) ),
			'C:7:"Message":242:{a:8:{s:9:"interface";b:1;s:8:"language";b:0;s:3:"key";s:10:"rawmessage";s:9:"keysToTry";a:1:{i:0;s:10:"rawmessage";}s:10:"parameters";a:1:{i:0;s:12:"{{PAGENAME}}";}s:6:"format";s:5:"parse";s:11:"useDatabase";b:1;s:8:"titlestr";s:7:"Testing";}}',
			'Testing',
		];
	}

	/**
	 * @dataProvider provideNewFromSpecifier
	 */
	public function testNewFromSpecifier( $valueFunc, $expectedText ) {
		$value = $valueFunc( $this );
		$message = Message::newFromSpecifier( $value );
		$this->assertInstanceOf( Message::class, $message );
		if ( $value instanceof Message ) {
			$this->assertInstanceOf( get_class( $value ), $message );
			$this->assertEquals( $value, $message );
		}
		$this->assertSame( $expectedText, $message->text() );
	}

	public static function provideNewFromSpecifier() {
		return [
			'string' => [
				static fn () => 'mainpage',
				'Main Page'
			],
			'array' => [
				static fn () => [ 'new-messages', 'foo', 'bar' ],
				'You have foo (bar).'
			],
			'Message' => [
				static fn () => new Message( 'new-messages', [ 'foo', 'bar' ] ),
				'You have foo (bar).'
			],
			'RawMessage' => [
				static fn () => new RawMessage( 'foo ($1)', [ 'bar' ] ),
				'foo (bar)'
			],
			'ApiMessage' => [
				static fn () => new ApiMessage( [ 'mainpage' ], 'code', [ 'data' ] ),
				'Main Page'
			],
			'MessageSpecifier' => [
				static function ( MessageTest $test ) {
					$messageSpecifier = $test->getMockForAbstractClass( MessageSpecifier::class );
					$messageSpecifier->method( 'getKey' )->willReturn( 'mainpage' );
					$messageSpecifier->method( 'getParams' )->willReturn( [] );
					return $messageSpecifier;
				},
				'Main Page'
			],
			'nested RawMessage' => [
				static fn () => [ new RawMessage( 'foo ($1)', [ 'bar' ] ) ],
				'foo (bar)'
			],
		];
	}

	public static function provideFallbackLanguageParsing() {
		return [
			[ 'en', '21 days' ],
			[ 'ru', '21 день' ],
			[ 'be', '21 days' ]
		];
	}

	/**
	 * Integration test for T268492
	 *
	 * @dataProvider provideFallbackLanguageParsing
	 */
	public function testFallbackLanguageParsing( $lang, $expected ) {
		$this->overrideConfigValue(
			MainConfigNames::MessagesDirs,
			array_merge(
				$this->getConfVar( MainConfigNames::MessagesDirs ),
				[ MW_INSTALL_PATH . '/tests/phpunit/data/Message' ]
			)
		);
		$text = ( new Message( 'test-days', [ 21 ] ) )
			->inLanguage( $lang )->text();
		$this->assertSame( $expected, $text );
	}

}
