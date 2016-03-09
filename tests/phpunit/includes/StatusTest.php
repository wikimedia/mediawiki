<?php

/**
 * @author Addshore
 */
class StatusTest extends MediaWikiLangTestCase {

	public function testCanConstruct() {
		new Status();
		$this->assertTrue( true );
	}

	/**
	 * @dataProvider provideValues
	 * @covers Status::newGood
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

	/**
	 * @covers Status::newFatal
	 */
	public function testNewFatalWithMessage() {
		$message = $this->getMockBuilder( 'Message' )
			->disableOriginalConstructor()
			->getMock();

		$status = Status::newFatal( $message );
		$this->assertFalse( $status->isGood() );
		$this->assertFalse( $status->isOK() );
		$this->assertEquals( $message, $status->getMessage() );
	}

	/**
	 * @covers Status::newFatal
	 */
	public function testNewFatalWithString() {
		$message = 'foo';
		$status = Status::newFatal( $message );
		$this->assertFalse( $status->isGood() );
		$this->assertFalse( $status->isOK() );
		$this->assertEquals( $message, $status->getMessage()->getKey() );
	}

	/**
	 *
	 */
	public function testOkAndErrors() {
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

	/**
	 * @dataProvider provideSetResult
	 * @covers Status::setResult
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
	 * @covers Status::isOk
	 */
	public function testIsOk( $ok ) {
		$status = new Status();
		$status->ok = $ok;
		$this->assertEquals( $ok, $status->isOK() );
	}

	public static function provideIsOk() {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * @covers Status::getValue
	 */
	public function testGetValue() {
		$status = new Status();
		$status->value = 'foobar';
		$this->assertEquals( 'foobar', $status->getValue() );
	}

	/**
	 * @dataProvider provideIsGood
	 * @covers Status::isGood
	 */
	public function testIsGood( $ok, $errors, $expected ) {
		$status = new Status();
		$status->ok = $ok;
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
	 * @covers Status::warning
	 * @covers Status::getWarningsArray
	 * @covers Status::getStatusArray
	 */
	public function testWarningWithMessage( $mockDetails ) {
		$status = new Status();
		$messages = $this->getMockMessages( $mockDetails );

		foreach ( $messages as $message ) {
			$status->warning( $message );
		}
		$warnings = $status->getWarningsArray();

		$this->assertEquals( count( $messages ), count( $warnings ) );
		foreach ( $messages as $key => $message ) {
			$expectedArray = array_merge( [ $message->getKey() ], $message->getParams() );
			$this->assertEquals( $warnings[$key], $expectedArray );
		}
	}

	/**
	 * @dataProvider provideMockMessageDetails
	 * @covers Status::error
	 * @covers Status::getErrorsArray
	 * @covers Status::getStatusArray
	 */
	public function testErrorWithMessage( $mockDetails ) {
		$status = new Status();
		$messages = $this->getMockMessages( $mockDetails );

		foreach ( $messages as $message ) {
			$status->error( $message );
		}
		$errors = $status->getErrorsArray();

		$this->assertEquals( count( $messages ), count( $errors ) );
		foreach ( $messages as $key => $message ) {
			$expectedArray = array_merge( [ $message->getKey() ], $message->getParams() );
			$this->assertEquals( $errors[$key], $expectedArray );
		}
	}

	/**
	 * @dataProvider provideMockMessageDetails
	 * @covers Status::fatal
	 * @covers Status::getErrorsArray
	 * @covers Status::getStatusArray
	 */
	public function testFatalWithMessage( $mockDetails ) {
		$status = new Status();
		$messages = $this->getMockMessages( $mockDetails );

		foreach ( $messages as $message ) {
			$status->fatal( $message );
		}
		$errors = $status->getErrorsArray();

		$this->assertEquals( count( $messages ), count( $errors ) );
		foreach ( $messages as $key => $message ) {
			$expectedArray = array_merge( [ $message->getKey() ], $message->getParams() );
			$this->assertEquals( $errors[$key], $expectedArray );
		}
		$this->assertFalse( $status->isOK() );
	}

	protected function getMockMessage( $key = 'key', $params = [] ) {
		$message = $this->getMockBuilder( 'Message' )
			->disableOriginalConstructor()
			->getMock();
		$message->expects( $this->atLeastOnce() )
			->method( 'getKey' )
			->will( $this->returnValue( $key ) );
		$message->expects( $this->atLeastOnce() )
			->method( 'getParams' )
			->will( $this->returnValue( $params ) );
		return $message;
	}

	/**
	 * @param array $messageDetails E.g. array( 'KEY' => array(/PARAMS/) )
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
			[ [ 'key1' => [ 'foo' => 'bar' ] ] ],
			[ [ 'key1' => [ 'foo' => 'bar' ], 'key2' => [ 'foo2' => 'bar2' ] ] ],
		];
	}

	/**
	 * @covers Status::merge
	 */
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

	/**
	 * @covers Status::merge
	 */
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

	/**
	 * @covers Status::hasMessage
	 */
	public function testHasMessage() {
		$status = new Status();
		$status->fatal( 'bad' );
		$status->fatal( wfMessage( 'bad-msg' ) );
		$this->assertTrue( $status->hasMessage( 'bad' ) );
		$this->assertTrue( $status->hasMessage( 'bad-msg' ) );
		$this->assertTrue( $status->hasMessage( wfMessage( 'bad-msg' ) ) );
		$this->assertFalse( $status->hasMessage( 'good' ) );
	}

	/**
	 * @dataProvider provideCleanParams
	 * @covers Status::cleanParams
	 */
	public function testCleanParams( $cleanCallback, $params, $expected ) {
		$method = new ReflectionMethod( 'Status', 'cleanParams' );
		$method->setAccessible( true );
		$status = new Status();
		$status->cleanCallback = $cleanCallback;

		$this->assertEquals( $expected, $method->invoke( $status, $params ) );
	}

	public static function provideCleanParams() {
		$cleanCallback = function ( $value ) {
			return '-' . $value . '-';
		};

		return [
			[ false, [ 'foo' => 'bar' ], [ 'foo' => 'bar' ] ],
			[ $cleanCallback, [ 'foo' => 'bar' ], [ 'foo' => '-bar-' ] ],
		];
	}

	/**
	 * @dataProvider provideGetWikiTextAndHtml
	 * @covers Status::getWikiText
	 */
	public function testGetWikiText(
		Status $status, $wikitext, $wrappedWikitext, $html, $wrappedHtml
	) {
		$this->assertEquals( $wikitext, $status->getWikiText() );

		$this->assertEquals( $wrappedWikitext, $status->getWikiText( 'wrap-short', 'wrap-long', 'qqx' ) );
	}

	/**
	 * @dataProvider provideGetWikiTextAndHtml
	 * @covers Status::getHtml
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
			"Internal error: Status::getWikiText called for a good result, this is incorrect\n",
			"(wrap-short: (internalerror_info: Status::getWikiText called for a good result, " .
				"this is incorrect\n))",
			"<p>Internal error: Status::getWikiText called for a good result, this is incorrect\n</p>",
			"<p>(wrap-short: (internalerror_info: Status::getWikiText called for a good result, " .
				"this is incorrect\n))\n</p>",
		];

		$status = new Status();
		$status->ok = false;
		$testCases['GoodButNoError'] = [
			$status,
			"Internal error: Status::getWikiText: Invalid result object: no error text but not OK\n",
			"(wrap-short: (internalerror_info: Status::getWikiText: Invalid result object: " .
				"no error text but not OK\n))",
			"<p>Internal error: Status::getWikiText: Invalid result object: no error text but not OK\n</p>",
			"<p>(wrap-short: (internalerror_info: Status::getWikiText: Invalid result object: " .
				"no error text but not OK\n))\n</p>",
		];

		$status = new Status();
		$status->warning( 'fooBar!' );
		$testCases['1StringWarning'] = [
			$status,
			"<fooBar!>",
			"(wrap-short: (fooBar!))",
			"<p>&lt;fooBar!&gt;\n</p>",
			"<p>(wrap-short: (fooBar!))\n</p>",
		];

		$status = new Status();
		$status->warning( 'fooBar!' );
		$status->warning( 'fooBar2!' );
		$testCases['2StringWarnings'] = [
			$status,
			"* <fooBar!>\n* <fooBar2!>\n",
			"(wrap-long: * (fooBar!)\n* (fooBar2!)\n)",
			"<ul><li> &lt;fooBar!&gt;</li>\n<li> &lt;fooBar2!&gt;</li></ul>\n",
			"<p>(wrap-long: * (fooBar!)\n</p>\n<ul><li> (fooBar2!)</li></ul>\n<p>)\n</p>",
		];

		$status = new Status();
		$status->warning( new Message( 'fooBar!', [ 'foo', 'bar' ] ) );
		$testCases['1MessageWarning'] = [
			$status,
			"<fooBar!>",
			"(wrap-short: (fooBar!: foo, bar))",
			"<p>&lt;fooBar!&gt;\n</p>",
			"<p>(wrap-short: (fooBar!: foo, bar))\n</p>",
		];

		$status = new Status();
		$status->warning( new Message( 'fooBar!', [ 'foo', 'bar' ] ) );
		$status->warning( new Message( 'fooBar2!' ) );
		$testCases['2MessageWarnings'] = [
			$status,
			"* <fooBar!>\n* <fooBar2!>\n",
			"(wrap-long: * (fooBar!: foo, bar)\n* (fooBar2!)\n)",
			"<ul><li> &lt;fooBar!&gt;</li>\n<li> &lt;fooBar2!&gt;</li></ul>\n",
			"<p>(wrap-long: * (fooBar!: foo, bar)\n</p>\n<ul><li> (fooBar2!)</li></ul>\n<p>)\n</p>",
		];

		return $testCases;
	}

	private static function sanitizedMessageParams( Message $message ) {
		return array_map( function ( $p ) {
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
	 * @covers Status::getMessage
	 */
	public function testGetMessage(
		Status $status, $expectedParams = [], $expectedKey, $expectedWrapper
	) {
		$message = $status->getMessage( null, null, 'qqx' );
		$this->assertInstanceOf( 'Message', $message );
		$this->assertEquals( $expectedParams, self::sanitizedMessageParams( $message ),
			'Message::getParams' );
		$this->assertEquals( $expectedKey, $message->getKey(), 'Message::getKey' );

		$message = $status->getMessage( 'wrapper-short', 'wrapper-long' );
		$this->assertInstanceOf( 'Message', $message );
		$this->assertEquals( $expectedWrapper, $message->getKey(), 'Message::getKey with wrappers' );
		$this->assertCount( 1, $message->getParams(), 'Message::getParams with wrappers' );

		$message = $status->getMessage( 'wrapper' );
		$this->assertInstanceOf( 'Message', $message );
		$this->assertEquals( 'wrapper', $message->getKey(), 'Message::getKey with wrappers' );
		$this->assertCount( 1, $message->getParams(), 'Message::getParams with wrappers' );

		$message = $status->getMessage( false, 'wrapper' );
		$this->assertInstanceOf( 'Message', $message );
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
			[ "Status::getMessage called for a good result, this is incorrect\n" ],
			'internalerror_info',
			'wrapper-short'
		];

		$status = new Status();
		$status->ok = false;
		$testCases['GoodButNoError'] = [
			$status,
			[ "Status::getMessage: Invalid result object: no error text but not OK\n" ],
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
		$status->warning( new Message( 'fooBar!', [ 'foo', 'bar' ] ) );
		$status->warning( new Message( 'fooBar2!' ) );
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
	 * @covers Status::replaceMessage
	 */
	public function testReplaceMessage() {
		$status = new Status();
		$message = new Message( 'key1', [ 'foo1', 'bar1' ] );
		$status->error( $message );
		$newMessage = new Message( 'key2', [ 'foo2', 'bar2' ] );

		$status->replaceMessage( $message, $newMessage );

		$this->assertEquals( $newMessage, $status->errors[0]['message'] );
	}

	/**
	 * @covers Status::getErrorMessage
	 */
	public function testGetErrorMessage() {
		$method = new ReflectionMethod( 'Status', 'getErrorMessage' );
		$method->setAccessible( true );
		$status = new Status();
		$key = 'foo';
		$params = [ 'bar' ];

		/** @var Message $message */
		$message = $method->invoke( $status, array_merge( [ $key ], $params ) );
		$this->assertInstanceOf( 'Message', $message );
		$this->assertEquals( $key, $message->getKey() );
		$this->assertEquals( $params, $message->getParams() );
	}

	/**
	 * @covers Status::getErrorMessageArray
	 */
	public function testGetErrorMessageArray() {
		$method = new ReflectionMethod( 'Status', 'getErrorMessageArray' );
		$method->setAccessible( true );
		$status = new Status();
		$key = 'foo';
		$params = [ 'bar' ];

		/** @var Message[] $messageArray */
		$messageArray = $method->invoke(
			$status,
			[
				array_merge( [ $key ], $params ),
				array_merge( [ $key ], $params )
			]
		);

		$this->assertInternalType( 'array', $messageArray );
		$this->assertCount( 2, $messageArray );
		foreach ( $messageArray as $message ) {
			$this->assertInstanceOf( 'Message', $message );
			$this->assertEquals( $key, $message->getKey() );
			$this->assertEquals( $params, $message->getParams() );
		}
	}

	/**
	 * @covers Status::getErrorsByType
	 */
	public function testGetErrorsByType() {
		$status = new Status();
		$warning = new Message( 'warning111' );
		$error = new Message( 'error111' );
		$status->warning( $warning );
		$status->error( $error );

		$warnings = $status->getErrorsByType( 'warning' );
		$errors = $status->getErrorsByType( 'error' );

		$this->assertCount( 1, $warnings );
		$this->assertCount( 1, $errors );
		$this->assertEquals( $warning, $warnings[0]['message'] );
		$this->assertEquals( $error, $errors[0]['message'] );
	}

	/**
	 * @covers Status::__wakeup
	 */
	public function testWakeUpSanitizesCallback() {
		$status = new Status();
		$status->cleanCallback = function ( $value ) {
			return '-' . $value . '-';
		};
		$status->__wakeup();
		$this->assertEquals( false, $status->cleanCallback );
	}

	/**
	 * @dataProvider provideNonObjectMessages
	 * @covers Status::getStatusArray
	 */
	public function testGetStatusArrayWithNonObjectMessages( $nonObjMsg ) {
		$status = new Status();
		if ( !array_key_exists( 1, $nonObjMsg ) ) {
			$status->warning( $nonObjMsg[0] );
		} else {
			$status->warning( $nonObjMsg[0], $nonObjMsg[1] );
		}

		$array = $status->getWarningsArray(); // We use getWarningsArray to access getStatusArray

		$this->assertEquals( 1, count( $array ) );
		$this->assertEquals( $nonObjMsg, $array[0] );
	}

	public static function provideNonObjectMessages() {
		return [
			[ [ 'ImaString', [ 'param1' => 'value1' ] ] ],
			[ [ 'ImaString' ] ],
		];
	}

}
