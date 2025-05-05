<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Language\MessageParser;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Status\StatusFormatter;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use Psr\Log\Test\TestLogger;
use Wikimedia\Message\MessageValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Status\StatusFormatter
 */
class StatusFormatterTest extends MediaWikiLangTestCase {

	private ?TestLogger $logger;

	protected function setUp(): void {
		parent::setUp();

		$this->logger = new TestLogger();
	}

	protected function tearDown(): void {
		parent::tearDown();
		$this->logger = null;
	}

	private function getFormatter( $lang = 'en' ) {
		$localizer = new class() implements MessageLocalizer {
			public $lang;

			public function msg( $key, ...$params ) {
				return wfMessage( $key, ...$params )->inLanguage( $this->lang );
			}
		};

		$cache = $this->createNoOpMock( MessageParser::class, [ 'parse' ] );
		$cache->method( 'parse' )->willReturnCallback(
			static function ( $text, ...$args ) {
				$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5 );
				return new ParserOutput( "<p>" . trim( $text ) . "\n</p>" );
			}
		);

		$localizer->lang = $lang;

		return new StatusFormatter( $localizer, $cache, $this->logger );
	}

	/**
	 * @dataProvider provideCleanParams
	 */
	public function testCleanParams( $cleanCallback, $params, $expected, $unexpected ) {
		$status = new StatusValue();
		$status->warning( 'ok', ...$params );

		$formatter = $this->getFormatter( 'qqx' );
		$options = [ 'cleanCallback' => $cleanCallback ];

		$wikitext = $formatter->getWikiText( $status, $options );
		$this->assertStringContainsString( $expected, $wikitext );
		$this->assertStringNotContainsString( $unexpected, $wikitext );

		$html = $formatter->getHTML( $status, $options );
		$this->assertStringContainsString( $expected, $html );
		$this->assertStringNotContainsString( $unexpected, $html );
	}

	public static function provideCleanParams() {
		$cleanCallback = static function ( $value ) {
			return 'xxx';
		};

		return [
			[ false, [ 'secret' ], 'secret', 'xxx' ],
			[ $cleanCallback, [ 'secret' ], 'xxx', 'secret' ],
		];
	}

	/**
	 * @dataProvider provideGetWikiTextAndHtml
	 */
	public function testGetWikiText(
		StatusValue $status, $wikitext, $wrappedWikitext, $html, $wrappedHtml
	) {
		$formatter = $this->getFormatter();
		$this->assertEquals( $wikitext, $formatter->getWikiText( $status ) );

		$this->assertEquals(
			$wrappedWikitext,
			$formatter->getWikiText(
				$status,
				[
					'shortContext' => 'wrap-short',
					'longContext' => 'wrap-long',
					'lang' => 'qqx',
				]
			)
		);
	}

	/**
	 * @dataProvider provideGetWikiTextAndHtml
	 */
	public function testGetHtml(
		StatusValue $status,
		$wikitext,
		$wrappedWikitext,
		$html,
		$wrappedHtml,
		?string $expectedWarning = null
	) {
		$formatter = $this->getFormatter();
		$this->assertEquals( $html, $formatter->getHTML( $status ) );

		$this->assertEquals(
			$wrappedHtml,
			$formatter->getHTML(
				$status,
				[
					'shortContext' => 'wrap-short',
					'longContext' => 'wrap-long',
					'lang' => 'qqx',
				]
			)
		);

		if ( $expectedWarning !== null ) {
			$this->assertTrue( $this->logger->hasWarningThatContains( $expectedWarning ) );
		} else {
			$this->assertFalse( $this->logger->hasWarningRecords() );
		}
	}

	/**
	 * @return array Array of arrays with values;
	 *    0 => status object
	 *    1 => expected string (with no context)
	 */
	public static function provideGetWikiTextAndHtml() {
		$testCases = [];

		$testCases['GoodStatus'] = [
			new StatusValue(),
			"Internal error: MediaWiki\Status\StatusFormatter::getWikiText called for a good result, this is incorrect&#10;",
			"(wrap-short: (internalerror_info: MediaWiki\Status\StatusFormatter::getWikiText called for a good result, " .
				"this is incorrect&#10;))",
			"<p>Internal error: MediaWiki\Status\StatusFormatter::getWikiText called for a good result, this is incorrect\n</p>",
			"<p>(wrap-short: (internalerror_info: MediaWiki\Status\StatusFormatter::getWikiText called for a good result, " .
				"this is incorrect\n))\n</p>",
			'MediaWiki\Status\StatusFormatter::getWikiText called for a good result, this is incorrect'
		];

		$status = new StatusValue();
		$status->setOK( false );
		$testCases['GoodButNoError'] = [
			$status,
			"Internal error: MediaWiki\Status\StatusFormatter::getWikiText: Invalid result object: no error text but not OK&#10;",
			"(wrap-short: (internalerror_info: MediaWiki\Status\StatusFormatter::getWikiText: Invalid result object: " .
				"no error text but not OK&#10;))",
			"<p>Internal error: MediaWiki\Status\StatusFormatter::getWikiText: Invalid result object: no error text but not OK\n</p>",
			"<p>(wrap-short: (internalerror_info: MediaWiki\Status\StatusFormatter::getWikiText: Invalid result object: " .
				"no error text but not OK\n))\n</p>",
			'MediaWiki\Status\StatusFormatter::getWikiText: Invalid result object: no error text but not OK'
		];

		$status = new StatusValue();
		$status->warning( 'fooBar!' );
		$testCases['1StringWarning'] = [
			$status,
			"⧼fooBar!⧽",
			"(wrap-short: (fooBar!))",
			"<p>⧼fooBar!⧽\n</p>",
			"<p>(wrap-short: (fooBar!))\n</p>",
		];

		$status = new StatusValue();
		$status->warning( 'fooBar!' );
		$status->warning( 'fooBar2!' );
		$testCases['2StringWarnings'] = [
			$status,
			"<ul>\n<li>\n⧼fooBar!⧽\n</li>\n<li>\n⧼fooBar2!⧽\n</li>\n</ul>\n",
			"(wrap-long: <ul>\n<li>\n(fooBar!)\n</li>\n<li>\n(fooBar2!)\n</li>\n</ul>\n)",
			"<p><ul>\n<li>\n⧼fooBar!⧽\n</li>\n<li>\n⧼fooBar2!⧽\n</li>\n</ul>\n</p>",
			"<p>(wrap-long: <ul>\n<li>\n(fooBar!)\n</li>\n<li>\n(fooBar2!)\n</li>\n</ul>\n)\n</p>",
		];

		$status = new StatusValue();
		$status->warning( new Message( 'fooBar!', [ 'foo', 'bar' ] ) );
		$testCases['1MessageWarning'] = [
			$status,
			"⧼fooBar!⧽",
			"(wrap-short: (fooBar!: foo, bar))",
			"<p>⧼fooBar!⧽\n</p>",
			"<p>(wrap-short: (fooBar!: foo, bar))\n</p>",
		];

		$status = new StatusValue();
		$status->warning( new Message( 'fooBar!', [ 'foo', 'bar' ] ) );
		$status->warning( new Message( 'fooBar2!' ) );
		$testCases['2MessageWarnings'] = [
			$status,
			"<ul>\n<li>\n⧼fooBar!⧽\n</li>\n<li>\n⧼fooBar2!⧽\n</li>\n</ul>\n",
			"(wrap-long: <ul>\n<li>\n(fooBar!: foo, bar)\n</li>\n<li>\n(fooBar2!)\n</li>\n</ul>\n)",
			"<p><ul>\n<li>\n⧼fooBar!⧽\n</li>\n<li>\n⧼fooBar2!⧽\n</li>\n</ul>\n</p>",
			"<p>(wrap-long: <ul>\n<li>\n(fooBar!: foo, bar)\n</li>\n<li>\n(fooBar2!)\n</li>\n</ul>\n)\n</p>",
		];

		return $testCases;
	}

	private static function sanitizedMessageParams( Message $message ) {
		return array_map( static function ( $p ) {
			return $p instanceof Message
				? [
					'key' => $p->getKey(),
					'params' => self::sanitizedMessageParams( $p ),
					'lang' => $p->getLanguage()->getCode(),
				]
				: $p;
		}, $message instanceof RawMessage ? $message->getParamsOfRawMessage() : $message->getParams() );
	}

	private static function sanitizedMessageKey( Message $message ) {
		return $message instanceof RawMessage ? $message->getTextOfRawMessage() : $message->getKey();
	}

	/**
	 * @dataProvider provideGetMessage
	 */
	public function testGetMessage(
		StatusValue $status,
		$expectedParams,
		$expectedKey,
		$expectedWrapper,
		?string $expectedWarning = null
	) {
		$formatter = $this->getFormatter();
		$message = $formatter->getMessage( $status, [ 'lang' => 'qqx' ] );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( $expectedParams, self::sanitizedMessageParams( $message ),
			'Message::getParams' );
		$this->assertEquals( $expectedKey, self::sanitizedMessageKey( $message ), 'Message::getKey' );

		$message = $formatter->getMessage(
			$status,
			[
				'shortContext' => 'wrapper-short',
				'longContext' => 'wrapper-long',
			]
		);
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( $expectedWrapper, $message->getKey(), 'Message::getKey with wrappers' );
		$this->assertCount( 1, $message->getParams(), 'Message::getParams with wrappers' );

		$message = $formatter->getMessage( $status, [ 'shortContext' => 'wrapper' ] );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( 'wrapper', $message->getKey(), 'Message::getKey with wrappers' );
		$this->assertCount( 1, $message->getParams(), 'Message::getParams with wrappers' );

		$message = $formatter->getMessage( $status, [ 'longContext' => 'wrapper' ] );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( 'wrapper', $message->getKey(), 'Message::getKey with wrappers' );
		$this->assertCount( 1, $message->getParams(), 'Message::getParams with wrappers' );

		if ( $expectedWarning !== null ) {
			$this->assertTrue( $this->logger->hasWarningThatContains( $expectedWarning ) );
		} else {
			$this->assertFalse( $this->logger->hasWarningRecords() );
		}
	}

	/**
	 * @return array Array of arrays with values;
	 *    0 => status object
	 *    1 => expected Message parameters (with no context)
	 *    2 => expected Message key
	 */
	public static function provideGetMessage() {
		$testCases = [];

		$testCases['GoodStatus'] = [
			new StatusValue(),
			[ "MediaWiki\Status\StatusFormatter::getMessage called for a good result, this is incorrect&#10;" ],
			'internalerror_info',
			'wrapper-short',
			'MediaWiki\Status\StatusFormatter::getMessage called for a good result, this is incorrect'
		];

		$status = new StatusValue();
		$status->setOK( false );
		$testCases['GoodButNoError'] = [
			$status,
			[ "MediaWiki\Status\StatusFormatter::getMessage: Invalid result object: no error text but not OK&#10;" ],
			'internalerror_info',
			'wrapper-short',
			'MediaWiki\Status\StatusFormatter::getMessage: Invalid result object: no error text but not OK'
		];

		$status = new StatusValue();
		$status->warning( 'fooBar!' );
		$testCases['1StringWarning'] = [
			$status,
			[],
			'fooBar!',
			'wrapper-short'
		];

		$status = new StatusValue();
		$status->warning( 'fooBar!' );
		$status->warning( 'fooBar2!' );
		$testCases[ '2StringWarnings' ] = [
			$status,
			[
				[ 'key' => 'fooBar!', 'params' => [], 'lang' => 'qqx' ],
				[ 'key' => 'fooBar2!', 'params' => [], 'lang' => 'qqx' ]
			],
			"* \$1\n* \$2",
			'wrapper-long'
		];

		$status = new StatusValue();
		$status->warning( new Message( 'fooBar!', [ 'foo', 'bar' ] ) );
		$testCases['1MessageWarning'] = [
			$status,
			[ 'foo', 'bar' ],
			'fooBar!',
			'wrapper-short'
		];

		$status = new StatusValue();
		$status->warning( new MessageValue( 'fooBar!', [ 'foo', 'bar' ] ) );
		$status->warning( new MessageValue( 'fooBar2!' ) );
		$testCases['2MessageWarnings'] = [
			$status,
			[
				[ 'key' => 'fooBar!', 'params' => [ 'foo', 'bar' ], 'lang' => 'qqx' ],
				[ 'key' => 'fooBar2!', 'params' => [], 'lang' => 'qqx' ]
			],
			"* \$1\n* \$2",
			'wrapper-long'
		];

		return $testCases;
	}

	/**
	 * @dataProvider provideGetPsr3MessageAndContext
	 */
	public function testGetPsr3MessageAndContext(
		array $errors,
		string $expectedMessage,
		array $expectedContext
	) {
		// set up a rawmessage_2 message, which is just like rawmessage but doesn't trigger
		// the special-casing in StatusFormatter::getPsr3MessageAndContext
		$this->setTemporaryHook( 'MessageCacheFetchOverrides', static function ( &$overrides ) {
			$overrides['rawmessage_2'] = 'rawmessage';
		}, false );

		$status = new StatusValue();
		foreach ( $errors as $error ) {
			$status->error( ...$error );
		}

		$formatter = $this->getFormatter();

		[ $actualMessage, $actualContext ] = $formatter->getPsr3MessageAndContext( $status );
		$this->assertSame( $expectedMessage, $actualMessage );
		$this->assertSame( $expectedContext, $actualContext );
	}

	public static function provideGetPsr3MessageAndContext() {
		return [
			// parameters to StatusValue::error() calls as array of arrays; expected message; expected context
			'no errors' => [
				[],
				"Internal error: MediaWiki\Status\StatusFormatter::getWikiText called for a good result, this is incorrect&#10;",
				[],
			],
			// make sure that the rawmessage_2 hack works as the following tests rely on it
			'rawmessage_2' => [
				[ [ 'rawmessage_2', 'foo' ] ],
				'{parameter1}',
				[ 'parameter1' => 'foo' ],
			],
			'two errors' => [
				[ [ 'rawmessage_2', 'foo' ], [ 'rawmessage_2', 'bar' ] ],
				"<ul>\n<li>\nfoo\n</li>\n<li>\nbar\n</li>\n</ul>\n",
				[],
			],
			'unknown subclass' => [
				// phpcs:ignore Squiz.WhiteSpace.ScopeClosingBrace.ContentBefore
				[ [ new class( 'rawmessage_2', [ 'foo' ] ) extends Message {} ] ],
				'foo',
				[],
			],
			'non-scalar parameter' => [
				[ [ new Message( 'rawmessage_2', [ new Message( 'rawmessage_2', [ 'foo' ] ) ] ) ] ],
				'foo',
				[],
			],
			'one parameter' => [
				[ [ 'apiwarn-invalidtitle', 'foo' ] ],
				'"{parameter1}" is not a valid title.',
				[ 'parameter1' => 'foo' ],
			],
			'multiple parameters' => [
				[ [ 'api-exception-trace', 'foo', 'bar', 'baz', 'boom' ] ],
				"{parameter1} at {parameter2}({parameter3})\n{parameter4}",
				[ 'parameter1' => 'foo', 'parameter2' => 'bar', 'parameter3' => 'baz', 'parameter4' => 'boom' ],
			],
			'formatted parameter' => [
				[ [ 'apiwarn-invalidtitle', Message::numParam( 1000000 ) ] ],
				'"{parameter1}" is not a valid title.',
				[ 'parameter1' => 1000000 ],
			],
			'rawmessage' => [
				[ [ 'rawmessage', 'foo' ] ],
				'foo',
				[],
			],
			'RawMessage' => [
				[ [ new RawMessage( 'foo $1 baz', [ 'bar' ] ) ] ],
				'foo {parameter1} baz',
				[ 'parameter1' => 'bar' ],
			],
		];
	}

	public function testGetErrorMessage() {
		$formatter = $this->getFormatter();
		/** @var StatusFormatter $formatter */
		$formatter = TestingAccessWrapper::newFromObject( $formatter );
		$key = 'foo';
		$params = [ 'bar' ];

		$message = $formatter->getErrorMessage( [ $key, ...$params ] );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( $key, $message->getKey() );
		$this->assertEquals( $params, $message->getParams() );
	}

	public function testGetErrorMessageComplexParam() {
		$formatter = $this->getFormatter();
		/** @var StatusFormatter $formatter */
		$formatter = TestingAccessWrapper::newFromObject( $formatter );
		$key = 'foo';
		$params = [ 'bar', Message::numParam( 5 ) ];

		$message = $formatter->getErrorMessage( [ $key, ...$params ] );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( $key, $message->getKey() );
		$this->assertEquals( $params, $message->getParams() );
	}

	public function testGetErrorMessageArray() {
		$formatter = $this->getFormatter();
		$formatter = TestingAccessWrapper::newFromObject( $formatter );
		$key = 'foo';
		$params = [ 'bar' ];

		/** @var Message[] $messageArray */
		$messageArray = $formatter->getErrorMessageArray(
			[
				[ $key, ...$params ],
				[ $key, ...$params ],
			]
		);

		$this->assertIsArray( $messageArray );
		$this->assertCount( 2, $messageArray );
		foreach ( $messageArray as $message ) {
			$this->assertInstanceOf( Message::class, $message );
			$this->assertEquals( $key, $message->getKey() );
			$this->assertEquals( $params, $message->getParams() );
		}
	}

	public function testUserLanguageNotLoaded() {
		// Confirm that the user language is not loaded from the database when
		// formatting an error in a specific language. Disable all hooks to prevent unrelated
		// access to user options.
		$this->setService( 'UserOptionsLookup', $this->createNoOpMock( UserOptionsLookup::class ) );
		$this->clearHooks( [ 'MessageCacheFetchOverrides', 'MessageCache::get' ] );
		$context = RequestContext::getMain();
		$user = new User;
		$user->setName( 'Test' );
		$context->setUser( $user );
		$this->getServiceContainer()
			->getFormatterFactory()
			->getStatusFormatter( RequestContext::getMain() )
			->getWikiText(
				StatusValue::newFatal( 'apierror-badquery' ),
				[ 'lang' => 'en' ]
			);
		$this->assertTrue( true );
	}
}
