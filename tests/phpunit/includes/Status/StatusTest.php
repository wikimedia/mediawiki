<?php

use MediaWiki\Language\RawMessage;
use MediaWiki\Status\Status;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Status\Status
 * @covers \StatusValue
 * @author Addshore
 */
class StatusTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideValues
	 */
	public function testNewGood( $value = null ) {
		$status = Status::newGood( $value );
		$this->assertTrue( $status->isGood() );
		$this->assertTrue( $status->isOK() );
		$this->assertEquals( $value, $status->getValue() );
	}

	public static function provideValues() {
		return [
			[],
			[ 'foo' ],
			[ [ 'foo' => 'bar' ] ],
			[ new Exception() ],
			[ 1234 ],
		];
	}

	public function testNewFatalWithMessage() {
		$message = $this->getMockMessage();
		$status = Status::newFatal( $message );
		$this->assertFalse( $status->isGood() );
		$this->assertFalse( $status->isOK() );
		$this->assertEquals( $message, $status->getMessage() );
	}

	public function testNewFatalWithString() {
		$message = 'foo';
		$status = Status::newFatal( $message );
		$this->assertFalse( $status->isGood() );
		$this->assertFalse( $status->isOK() );
		$this->assertEquals( $message, $status->getMessage()->getKey() );
	}

	public function testOkAndErrorsGetters() {
		$status = Status::newGood( 'foo' );
		$this->assertTrue( $status->ok );
		$status = Status::newFatal( 'foo', 1, 2 );
		$this->assertFalse( $status->ok );
		$this->assertArrayEquals(
			[
				[
					'type' => 'error',
					'message' => 'foo',
					'params' => [ 1, 2 ]
				]
			],
			$status->errors
		);
	}

	public function testOkSetter() {
		$status = new Status();
		$status->ok = false;
		$this->assertFalse( $status->isOK() );
		$status->ok = true;
		$this->assertTrue( $status->isOK() );
	}

	/**
	 * @dataProvider provideSetResult
	 */
	public function testSetResult( $ok, $value = null ) {
		$status = new Status();
		$status->setResult( $ok, $value );
		$this->assertEquals( $ok, $status->isOK() );
		$this->assertEquals( $value, $status->getValue() );
	}

	public static function provideSetResult() {
		return [
			[ true ],
			[ false ],
			[ true, 'value' ],
			[ false, 'value' ],
		];
	}

	/**
	 * @dataProvider provideIsOk
	 */
	public function testIsOk( $ok ) {
		$status = new Status();
		$status->setOK( $ok );
		$this->assertEquals( $ok, $status->isOK() );
	}

	public static function provideIsOk() {
		return [
			[ true ],
			[ false ],
		];
	}

	public function testGetValue() {
		$status = new Status();
		$status->value = 'foobar';
		$this->assertEquals( 'foobar', $status->getValue() );
	}

	/**
	 * @dataProvider provideIsGood
	 */
	public function testIsGood( $ok, $errors, $expected ) {
		$status = new Status();
		$status->setOK( $ok );
		foreach ( $errors as $error ) {
			$status->warning( $error );
		}
		$this->assertEquals( $expected, $status->isGood() );
	}

	public static function provideIsGood() {
		return [
			[ true, [], true ],
			[ true, [ 'foo' ], false ],
			[ false, [], false ],
			[ false, [ 'foo' ], false ],
		];
	}

	/**
	 * @dataProvider provideMockMessageDetails
	 */
	public function testWarningWithMessage( $mockDetails ) {
		$status = new Status();
		$messages = $this->getMockMessages( $mockDetails );

		foreach ( $messages as $message ) {
			$status->warning( $message );
		}
		$warnings = $status->getWarningsArray();

		$this->assertSameSize( $messages, $warnings );
		foreach ( $messages as $key => $message ) {
			$expectedArray = [ $message->getKey(), ...$message->getParams() ];
			$this->assertEquals( $expectedArray, $warnings[$key] );
		}
	}

	/**
	 * @dataProvider provideMockMessageDetails
	 */
	public function testErrorWithMessage( $mockDetails ) {
		$status = new Status();
		$messages = $this->getMockMessages( $mockDetails );

		foreach ( $messages as $message ) {
			$status->error( $message );
		}
		$errors = $status->getErrorsArray();

		$this->assertSameSize( $messages, $errors );
		foreach ( $messages as $key => $message ) {
			$expectedArray = [ $message->getKey(), ...$message->getParams() ];
			$this->assertEquals( $expectedArray, $errors[$key] );
		}
	}

	/**
	 * @dataProvider provideMockMessageDetails
	 */
	public function testFatalWithMessage( $mockDetails ) {
		$status = new Status();
		$messages = $this->getMockMessages( $mockDetails );

		foreach ( $messages as $message ) {
			$status->fatal( $message );
		}
		$errors = $status->getErrorsArray();

		$this->assertSameSize( $messages, $errors );
		foreach ( $messages as $key => $message ) {
			$expectedArray = [ $message->getKey(), ...$message->getParams() ];
			$this->assertEquals( $expectedArray, $errors[$key] );
		}
		$this->assertStatusNotOK( $status );
	}

	/**
	 * @param array $messageDetails E.g. [ 'KEY' => [ /PARAMS/ ] ]
	 * @return Message[]
	 */
	protected function getMockMessages( $messageDetails ) {
		$messages = [];
		foreach ( $messageDetails as $key => $paramsArray ) {
			$messages[] = $this->getMockMessage( $key, $paramsArray );
		}
		return $messages;
	}

	public static function provideMockMessageDetails() {
		return [
			[ [ 'key1' => [ 'bar' ] ] ],
			[ [ 'key1' => [ 'bar' ], 'key2' => [ 'bar2' ] ] ],
		];
	}

	public function testMerge() {
		$status1 = new Status();
		$status2 = new Status();
		$message1 = $this->getMockMessage( 'warn1' );
		$message2 = $this->getMockMessage( 'error2' );
		$status1->warning( $message1 );
		$status2->error( $message2 );

		$status1->merge( $status2 );
		$this->assertEquals(
			2,
			count( $status1->getWarningsArray() ) + count( $status1->getErrorsArray() )
		);
	}

	public function testMergeWithOverwriteValue() {
		$status1 = new Status();
		$status2 = new Status();
		$message1 = $this->getMockMessage( 'warn1' );
		$message2 = $this->getMockMessage( 'error2' );
		$status1->warning( $message1 );
		$status2->error( $message2 );
		$status2->value = 'FooValue';

		$status1->merge( $status2, true );
		$this->assertEquals(
			2,
			count( $status1->getWarningsArray() ) + count( $status1->getErrorsArray() )
		);
		$this->assertEquals( 'FooValue', $status1->getValue() );
	}

	public function testHasMessage() {
		$status = new Status();
		$status->fatal( 'bad' );
		$status->fatal( wfMessage( 'bad-msg' ) );
		$status->fatal( new MessageValue( 'bad-msg-value' ) );
		$this->assertTrue( $status->hasMessage( 'bad' ) );
		$this->assertTrue( $status->hasMessage( 'bad-msg' ) );
		$this->expectDeprecationAndContinue( '/Passing MessageSpecifier/' );
		$this->assertTrue( $status->hasMessage( wfMessage( 'bad-msg' ) ) );
		$this->assertTrue( $status->hasMessage( wfMessage( 'bad-msg-value' ) ) );
		$this->expectDeprecationAndContinue( '/Passing MessageValue/' );
		$this->assertTrue( $status->hasMessage( new MessageValue( 'bad-msg' ) ) );
		$this->assertTrue( $status->hasMessage( new MessageValue( 'bad-msg-value' ) ) );
		$this->assertFalse( $status->hasMessage( 'good' ) );
	}

	public function testHasMessagesExcept() {
		$status = new Status();
		$status->fatal( 'bad' );
		$status->fatal( wfMessage( 'bad-msg' ) );
		$status->fatal( new MessageValue( 'bad-msg-value' ) );
		$this->assertTrue( $status->hasMessagesExcept( 'good' ) );
		$this->assertTrue( $status->hasMessagesExcept( 'bad' ) );
		$this->assertFalse( $status->hasMessagesExcept(
			'bad', 'bad-msg', 'bad-msg-value' ) );
		$this->assertFalse( $status->hasMessagesExcept(
			'good', 'bad', 'bad-msg', 'bad-msg-value' ) );
		$this->expectDeprecationAndContinue( '/Passing MessageSpecifier/' );
		$this->expectDeprecationAndContinue( '/Passing MessageValue/' );
		$this->assertFalse( $status->hasMessagesExcept(
			wfMessage( 'bad' ), new MessageValue( 'bad-msg' ), 'bad-msg-value' ) );
	}

	/**
	 * @dataProvider provideCleanParams
	 */
	public function testCleanParams( $cleanCallback, $params, $expected, $unexpected ) {
		$this->setUserLang( 'qqx' );

		$status = new Status();
		$status->cleanCallback = $cleanCallback;
		$status->warning( 'ok', ...$params );

		$wikitext = $status->getWikiText();
		$this->assertStringContainsString( $expected, $wikitext );
		$this->assertStringNotContainsString( $unexpected, $wikitext );

		$html = $status->getHTML();
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
		Status $status, $wikitext, $wrappedWikitext, $html, $wrappedHtml
	) {
		$this->assertEquals( $wikitext, $status->getWikiText() );

		$this->assertEquals( $wrappedWikitext, $status->getWikiText( 'wrap-short', 'wrap-long', 'qqx' ) );
	}

	/**
	 * @dataProvider provideGetWikiTextAndHtml
	 */
	public function testGetHtml(
		Status $status, $wikitext, $wrappedWikitext, $html, $wrappedHtml
	) {
		$this->assertEquals( $html, $status->getHTML() );

		$this->assertEquals( $wrappedHtml, $status->getHTML( 'wrap-short', 'wrap-long', 'qqx' ) );
	}

	/**
	 * @return array Array of arrays with values;
	 *    0 => status object
	 *    1 => expected string (with no context)
	 */
	public static function provideGetWikiTextAndHtml() {
		$testCases = [];

		$testCases['GoodStatus'] = [
			new Status(),
			"Internal error: MediaWiki\Status\StatusFormatter::getWikiText called for a good result, this is incorrect&#10;",
			"(wrap-short: (internalerror_info: MediaWiki\Status\StatusFormatter::getWikiText called for a good result, " .
				"this is incorrect&#10;))",
			"<p>Internal error: MediaWiki\Status\StatusFormatter::getWikiText called for a good result, this is incorrect&#10;\n</p>",
			"<p>(wrap-short: (internalerror_info: MediaWiki\Status\StatusFormatter::getWikiText called for a good result, " .
				"this is incorrect&#10;))\n</p>",
		];

		$status = new Status();
		$status->setOK( false );
		$testCases['GoodButNoError'] = [
			$status,
			"Internal error: MediaWiki\Status\StatusFormatter::getWikiText: Invalid result object: no error text but not OK&#10;",
			"(wrap-short: (internalerror_info: MediaWiki\Status\StatusFormatter::getWikiText: Invalid result object: " .
				"no error text but not OK&#10;))",
			"<p>Internal error: MediaWiki\Status\StatusFormatter::getWikiText: Invalid result object: no error text but not OK&#10;\n</p>",
			"<p>(wrap-short: (internalerror_info: MediaWiki\Status\StatusFormatter::getWikiText: Invalid result object: " .
				"no error text but not OK&#10;))\n</p>",
		];

		$status = new Status();
		$status->warning( 'fooBar!' );
		$testCases['1StringWarning'] = [
			$status,
			"⧼fooBar!⧽",
			"(wrap-short: (fooBar!))",
			"<p>⧼fooBar!⧽\n</p>",
			"<p>(wrap-short: (fooBar!))\n</p>",
		];

		$status = new Status();
		$status->warning( 'fooBar!' );
		$status->warning( 'fooBar2!' );
		$testCases['2StringWarnings'] = [
			$status,
			"* ⧼fooBar!⧽\n* ⧼fooBar2!⧽\n",
			"(wrap-long: * (fooBar!)\n* (fooBar2!)\n)",
			"<ul><li>⧼fooBar!⧽</li>\n<li>⧼fooBar2!⧽</li></ul>\n",
			"<p>(wrap-long: * (fooBar!)\n</p>\n<ul><li>(fooBar2!)</li></ul>\n<p>)\n</p>",
		];

		$status = new Status();
		$status->warning( new Message( 'fooBar!', [ 'foo', 'bar' ] ) );
		$testCases['1MessageWarning'] = [
			$status,
			"⧼fooBar!⧽",
			"(wrap-short: (fooBar!: foo, bar))",
			"<p>⧼fooBar!⧽\n</p>",
			"<p>(wrap-short: (fooBar!: foo, bar))\n</p>",
		];

		$status = new Status();
		$status->warning( new Message( 'fooBar!', [ 'foo', 'bar' ] ) );
		$status->warning( new Message( 'fooBar2!' ) );
		$testCases['2MessageWarnings'] = [
			$status,
			"* ⧼fooBar!⧽\n* ⧼fooBar2!⧽\n",
			"(wrap-long: * (fooBar!: foo, bar)\n* (fooBar2!)\n)",
			"<ul><li>⧼fooBar!⧽</li>\n<li>⧼fooBar2!⧽</li></ul>\n",
			"<p>(wrap-long: * (fooBar!: foo, bar)\n</p>\n<ul><li>(fooBar2!)</li></ul>\n<p>)\n</p>",
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
		}, $message->getParams() );
	}

	/**
	 * @dataProvider provideGetMessage
	 */
	public function testGetMessage(
		Status $status, $expectedParams, $expectedKey, $expectedWrapper
	) {
		$message = $status->getMessage( null, null, 'qqx' );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( $expectedParams, self::sanitizedMessageParams( $message ),
			'Message::getParams' );
		$this->assertEquals( $expectedKey, $message->getKey(), 'Message::getKey' );

		$message = $status->getMessage( 'wrapper-short', 'wrapper-long' );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( $expectedWrapper, $message->getKey(), 'Message::getKey with wrappers' );
		$this->assertCount( 1, $message->getParams(), 'Message::getParams with wrappers' );

		$message = $status->getMessage( 'wrapper' );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( 'wrapper', $message->getKey(), 'Message::getKey with wrappers' );
		$this->assertCount( 1, $message->getParams(), 'Message::getParams with wrappers' );

		$message = $status->getMessage( false, 'wrapper' );
		$this->assertInstanceOf( Message::class, $message );
		$this->assertEquals( 'wrapper', $message->getKey(), 'Message::getKey with wrappers' );
		$this->assertCount( 1, $message->getParams(), 'Message::getParams with wrappers' );
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
			new Status(),
			[ "MediaWiki\Status\StatusFormatter::getMessage called for a good result, this is incorrect&#10;" ],
			'internalerror_info',
			'wrapper-short'
		];

		$status = new Status();
		$status->setOK( false );
		$testCases['GoodButNoError'] = [
			$status,
			[ "MediaWiki\Status\StatusFormatter::getMessage: Invalid result object: no error text but not OK&#10;" ],
			'internalerror_info',
			'wrapper-short'
		];

		$status = new Status();
		$status->warning( 'fooBar!' );
		$testCases['1StringWarning'] = [
			$status,
			[],
			'fooBar!',
			'wrapper-short'
		];

		$status = new Status();
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

		$status = new Status();
		$status->warning( new Message( 'fooBar!', [ 'foo', 'bar' ] ) );
		$testCases['1MessageWarning'] = [
			$status,
			[ 'foo', 'bar' ],
			'fooBar!',
			'wrapper-short'
		];

		$status = new Status();
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
		// the special-casing in Status::getPsr3MessageAndContext
		$this->setTemporaryHook( 'MessageCacheFetchOverrides', static function ( &$overrides ) {
			$overrides['rawmessage_2'] = 'rawmessage';
		}, false );

		$status = new Status();
		foreach ( $errors as $error ) {
			$status->error( ...$error );
		}
		[ $actualMessage, $actualContext ] = $status->getPsr3MessageAndContext();
		$this->assertSame( $expectedMessage, $actualMessage );
		$this->assertSame( $expectedContext, $actualContext );
	}

	public static function provideGetPsr3MessageAndContext() {
		return [
			// parameters to Status::error() calls as array of arrays; expected message; expected context
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
				"* foo\n* bar\n",
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

	public function testReplaceMessageObj() {
		$this->expectDeprecationAndContinue( '/Passing MessageSpecifier/' );

		$status = new Status();
		$message = new Message( 'key1', [ 'foo1', 'bar1' ] );
		$status->error( $message );
		$newMessage = new Message( 'key2', [ 'foo2', 'bar2' ] );

		// Replacing by searching for the same Message object works
		$status->replaceMessage( $message, $newMessage );
		$this->assertSame( $newMessage, $status->errors[0]['message'] );

		$messageB = new Message( 'key-b', [ 'foo1', 'bar1' ] );
		$status->error( $messageB );

		// Replacing by searching for a different but equivalent Message object DOES NOT WORK
		// (that's why this is deprecated)
		$status->replaceMessage( new Message( 'key-b' ), 'huh' );
		$status->replaceMessage( new Message( 'key-b', [ 'foo1', 'bar1' ] ), 'huh' );
		$this->assertSame( $messageB, $status->errors[1]['message'] );
	}

	public function testReplaceMessageValue() {
		$this->expectDeprecationAndContinue( '/Passing MessageValue/' );

		$status = new Status();
		$messageVal = new MessageValue( 'key1', [ 'foo1', 'bar1' ] );
		$status->error( $messageVal );
		$newMessageVal = new MessageValue( 'key2', [ 'foo2', 'bar2' ] );

		$status->replaceMessage( $messageVal, $newMessageVal );

		// Replacing by searching for a MessageValue DOES NOT WORK at all
		// (that's why this is deprecated)
		$conv = new \MediaWiki\Message\Converter;
		$this->assertEquals( $messageVal, $conv->convertMessage( $status->errors[0]['message'] ) );
	}

	public function testReplaceMessageByKey() {
		$status = new Status();

		$status->error( new Message( 'key1', [ 'foo1', 'bar1' ] ) );
		$newMessage = new Message( 'key2', [ 'foo2', 'bar2' ] );

		$status->replaceMessage( 'key1', $newMessage );

		$this->assertEquals( $newMessage, $status->errors[0]['message'] );
	}

	public function testWakeUpSanitizesCallback() {
		$status = new Status();
		$status->cleanCallback = static function ( $value ) {
			return '-' . $value . '-';
		};
		$status->__wakeup();
		$this->assertFalse( $status->cleanCallback );
	}

	/**
	 * @dataProvider provideNonObjectMessages
	 */
	public function testGetStatusArrayWithNonObjectMessages( $nonObjMsg ) {
		$status = new Status();
		if ( !array_key_exists( 1, $nonObjMsg ) ) {
			$status->warning( $nonObjMsg[0] );
		} else {
			$status->warning( $nonObjMsg[0], $nonObjMsg[1] );
		}

		$array = $status->getWarningsArray(); // We use getWarningsArray to access getStatusArray

		$this->assertCount( 1, $array );
		$this->assertEquals( $nonObjMsg, $array[0] );
	}

	public static function provideNonObjectMessages() {
		return [
			[ [ 'ImaString', [ 'param1' => 'value1' ] ] ],
			[ [ 'ImaString' ] ],
		];
	}

	/**
	 * @dataProvider provideErrorsWarningsOnly
	 */
	public function testGetErrorsWarningsOnlyStatus( $errorText, $warningText, $type, $errorResult,
		$warningResult
	) {
		$status = Status::newGood();
		if ( $errorText ) {
			$status->fatal( $errorText );
		}
		if ( $warningText ) {
			$status->warning( $warningText );
		}
		$testStatus = $status->splitByErrorType()[$type];
		$this->assertEquals( $errorResult, $testStatus->getErrorsByType( 'error' ) );
		$this->assertEquals( $warningResult, $testStatus->getErrorsByType( 'warning' ) );
	}

	public static function provideErrorsWarningsOnly() {
		return [
			[
				'Just an error',
				'Just a warning',
				0,
				[
					0 => [
						'type' => 'error',
						'message' => 'Just an error',
						'params' => []
					],
				],
				[],
			], [
				'Just an error',
				'Just a warning',
				1,
				[],
				[
					0 => [
						'type' => 'warning',
						'message' => 'Just a warning',
						'params' => []
					],
				],
			], [
				null,
				null,
				1,
				[],
				[],
			], [
				null,
				null,
				0,
				[],
				[],
			]
		];
	}

	/**
	 * Regression test for interference between cloning and references.
	 * @coversNothing
	 */
	public function testWrapAndSplitByErrorType() {
		$sv = StatusValue::newFatal( 'fatal' );
		$sv->warning( 'warning' );
		$s = Status::wrap( $sv );
		[ $se, $sw ] = $s->splitByErrorType();
		$this->assertTrue( $s->hasMessage( 'fatal' ) );
		$this->assertTrue( $s->hasMessage( 'warning' ) );
		$this->assertFalse( $s->isOK() );
		$this->assertTrue( $se->hasMessage( 'fatal' ) );
		$this->assertFalse( $se->hasMessage( 'warning' ) );
		$this->assertFalse( $s->isOK() );
		$this->assertFalse( $sw->hasMessage( 'fatal' ) );
		$this->assertTrue( $sw->hasMessage( 'warning' ) );
		$this->assertTrue( $sw->isOK() );
	}

	public function testSetContext() {
		$status = Status::newFatal( 'foo' );
		$status->fatal( 'bar' );

		$messageLocalizer = $this->createNoOpMock( IContextSource::class, [ 'msg' ] );
		$messageLocalizer->expects( $this->atLeastOnce() )
			->method( 'msg' )
			->willReturnCallback( static function ( $key ) {
				return new RawMessage( $key );
			} );

		$status->setMessageLocalizer( $messageLocalizer );
		$status->getWikiText();
		$status->getWikiText( false, false, 'en' );
		$status->getWikiText( 'wrap-short', 'wrap-long' );
	}

	public static function provideDuplicates() {
		yield [ [ 'foo', 1, 2 ], [ 'foo', 1, 2 ] ];
		$message = new Message( 'foo', [ 1, 2 ] );
		yield [ $message, $message ];
		yield [ $message, [ 'foo', 1, 2 ] ];
		yield [ [ 'foo', 1, 2 ], $message ];
	}

	/**
	 * @dataProvider provideDuplicates
	 */
	public function testDuplicateError( $error1, $error2 ) {
		$status = Status::newGood();
		if ( $error1 instanceof MessageSpecifier ) {
			$status->error( $error1 );
			$expected = [
				'type' => 'error',
				'message' => $error1,
				'params' => []
			];
		} else {
			$status->error( ...$error1 );
			$message1 = $error1[0];
			array_shift( $error1 );
			$expected = [
				'type' => 'error',
				'message' => $message1,
				'params' => $error1
			];
		}
		if ( $error2 instanceof MessageSpecifier ) {
			$status->error( $error2 );
		} else {
			$message2 = $error2[0];
			array_shift( $error2 );
			$status->error( $message2, ...$error2 );
		}
		$this->assertArrayEquals( [ $expected ], $status->errors );
	}

	public function testDuplicateWarning() {
		$status = Status::newGood();
		$status->warning( 'foo', 1, 2 );
		$status->warning( 'foo', 1, 2 );
		$this->assertArrayEquals(
			[
				[
					'type' => 'warning',
					'message' => 'foo',
					'params' => [ 1, 2 ]
				]
			],
			$status->errors
		);
	}

	public function testErrorNotDowngradedToWarning() {
		$status = Status::newGood();
		$status->error( 'foo', 1, 2 );
		$status->warning( 'foo', 1, 2 );
		$this->assertArrayEquals(
			[
				[
					'type' => 'error',
					'message' => 'foo',
					'params' => [ 1, 2 ]
				]
			],
			$status->errors
		);
	}

	public function testErrorNotDowngradedToWarningMessage() {
		$status = Status::newGood();
		$message = new Message( 'foo', [ 1, 2 ] );
		$status->error( $message );
		$status->warning( $message );
		$this->assertArrayEquals(
			[
				[
					'type' => 'error',
					'message' => $message,
					'params' => []
				]
			],
			$status->errors
		);
	}

	public function testWarningUpgradedToError() {
		$status = Status::newGood();
		$status->warning( 'foo', 1, 2 );
		$status->error( 'foo', 1, 2 );
		$this->assertArrayEquals(
			[
				[
					'type' => 'error',
					'message' => 'foo',
					'params' => [ 1, 2 ]
				]
			],
			$status->errors
		);
	}

	public function testWarningUpgradedToErrorMessage() {
		$status = Status::newGood();
		$message = new Message( 'foo', [ 1, 2 ] );
		$status->warning( $message );
		$status->error( $message );
		$this->assertArrayEquals(
			[
				[
					'type' => 'error',
					'message' => $message,
					'params' => []
				]
			],
			$status->errors
		);
	}

	/**
	 * Ensure that two MessageSpecifiers that have the same key and params are considered
	 * identical even if they are different instances.
	 */
	public function testCompareMessages() {
		$status = Status::newGood();
		$message1 = new Message( 'foo', [ 1, 2 ] );
		$status->error( $message1 );
		$message2 = new Message( 'foo', [ 1, 2 ] );
		$status->error( $message2 );
		$this->assertCount( 1, $status->errors );
	}

	public function testDuplicateMerge() {
		$status1 = Status::newGood();
		$status1->error( 'cat', 1, 2 );
		$status1->warning( 'dog', 3, 4 );
		$status2 = Status::newGood();
		$status2->warning( 'cat', 1, 2 );
		$status1->error( 'dog', 3, 4 );
		$status2->warning( 'rabbit', 5, 6 );
		$status1->merge( $status2 );
		$this->assertArrayEquals(
			[
				[
					'type' => 'error',
					'message' => 'cat',
					'params' => [ 1, 2 ]
				],
				[
					'type' => 'error',
					'message' => 'dog',
					'params' => [ 3, 4 ]
				],
				[
					'type' => 'warning',
					'message' => 'rabbit',
					'params' => [ 5, 6 ]
				]
			],
			$status1->errors
		);
	}

	public function testNotDuplicateIfKeyDiffers() {
		$status = Status::newGood();
		$status->error( 'foo', 1, 2 );
		$status->error( 'bar', 1, 2 );
		$this->assertArrayEquals(
			[
				[
					'type' => 'error',
					'message' => 'foo',
					'params' => [ 1, 2 ]
				],
				[
					'type' => 'error',
					'message' => 'bar',
					'params' => [ 1, 2 ]
				]
			],
			$status->errors
		);
	}

	public function testNotDuplicateIfParamsDiffer() {
		$status = Status::newGood();
		$status->error( 'foo', 1, 2 );
		$status->error( 'foo', 3, 4 );
		$this->assertArrayEquals( [
			[
				'type' => 'error',
				'message' => 'foo',
				'params' => [ 1, 2 ]
			],
			[
				'type' => 'error',
				'message' => 'foo',
				'params' => [ 3, 4 ]
			]
		], $status->errors );
	}

	public function testToString() {
		$loremIpsum = 'Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor' .
			' incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud ' .
			'exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat. Quis aute iure ' .
			'reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
		$abc = [
			'x' => [ 'a', 'b', 'c' ],
			'z' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ ABCDEFGHIJKLMNOPQRSTUVWXYZ ' .
				'ABCDEFGHIJKLMNOPQRSTUVWXYZ ABCDEFGHIJKLMNOPQRSTUVWXYZ '
		];

		// This is a debug method, we don't care about the exact output. But it shouldn't cause
		// an error as it's called in various logging code.
		$this->expectNotToPerformAssertions();
		(string)Status::newGood();
		(string)Status::newGood( new MessageValue( 'foo' ) );
		(string)Status::newFatal( 'foo' );
		(string)Status::newFatal( 'foo', $loremIpsum, $abc );
		(string)Status::newFatal( wfMessage( 'foo' ) );
		(string)( Status::newFatal( 'foo' )->fatal( 'bar' ) );

		$status = Status::newGood();
		$status->warning( 'foo', $loremIpsum );
		$status->error( 'bar', $abc );
		(string)$status;
	}
}
