<?php

/**
 * @author Adam Shorland
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
		return array(
			array(),
			array( 'foo' ),
			array( array( 'foo' => 'bar' ) ),
			array( new Exception() ),
			array( 1234 ),
		);
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
		return array(
			array( true ),
			array( false ),
			array( true, 'value' ),
			array( false, 'value' ),
		);
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
		return array(
			array( true ),
			array( false ),
		);
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
		$status->errors = $errors;
		$this->assertEquals( $expected, $status->isGood() );
	}

	public static function provideIsGood() {
		return array(
			array( true, array(), true ),
			array( true, array( 'foo' ), false ),
			array( false, array(), false ),
			array( false, array( 'foo' ), false ),
		);
	}

	/**
	 * @dataProvider provideMockMessageDetails
	 * @covers Status::warning
	 * @covers Status::getWarningsArray
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
			$expectedArray = array_merge( array( $message->getKey() ), $message->getParams() );
			$this->assertEquals( $warnings[$key], $expectedArray );
		}
	}

	/**
	 * @dataProvider provideMockMessageDetails
	 * @covers Status::error
	 * @covers Status::getErrorsArray
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
			$expectedArray = array_merge( array( $message->getKey() ), $message->getParams() );
			$this->assertEquals( $errors[$key], $expectedArray );
		}
	}

	protected function getMockMessage( $key = 'key', $params = array() ) {
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
	 * @param array $messageDetails eg. array( 'KEY' => array(/PARAMS/) )
	 * @return Message[]
	 */
	protected function getMockMessages( $messageDetails ) {
		$messages = array();
		foreach ( $messageDetails as $key => $paramsArray ) {
			$messages[] = $this->getMockMessage( $key, $paramsArray );
		}
		return $messages;
	}

	public static function provideMockMessageDetails() {
		return array(
			array( array( 'key1' => array( 'foo' => 'bar' ) ) ),
			array( array( 'key1' => array( 'foo' => 'bar' ), 'key2' => array( 'foo2' => 'bar2' ) ) ),
		);
	}

	/**
	 * @covers Status::merge
	 * @todo test merge with $overwriteValue true
	 */
	public function testMerge() {
		$status1 = new Status();
		$status2 = new Status();
		$message1 = $this->getMockMessage( 'warn1' );
		$message2 = $this->getMockMessage( 'error2' );
		$status1->warning( $message1 );
		$status2->error( $message2 );

		$status1->merge( $status2 );
		$this->assertEquals( 2, count( $status1->getWarningsArray() ) + count( $status1->getErrorsArray() ) );
	}

	/**
	 * @covers Status::hasMessage
	 */
	public function testHasMessage() {
		$status = new Status();
		$status->fatal( 'bad' );
		$this->assertTrue( $status->hasMessage( 'bad' ) );
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

	/**
	 * @todo test cleanParams with a callback
	 */
	public static function provideCleanParams() {
		return array(
			array( false, array( 'foo' => 'bar' ), array( 'foo' => 'bar' ) ),
		);
	}

	/**
	 * @dataProvider provideGetWikiText
	 * @covers Status::getWikiText
	 * @todo test long and short context messages generated through this method
	 *       this can not really be done now due to use of wfMessage()->plain()
	 *       It is possible to mock such methods but only if namespaces are used
	 */
	public function testGetWikiText( Status $status, $expected ) {
		$this->assertEquals( $expected, $status->getWikiText() );
	}

	/**
	 * @return array of arrays with values;
	 *    0 => status object
	 *    1 => expected string (with no context)
	 */
	public static function provideGetWikiText() {
		$testCases = array();

		$testCases[ 'GoodStatus' ] = array(
			new Status(),
			"Internal error: Status::getWikiText called for a good result, this is incorrect\n",
		);

		$status = new Status();
		$status->ok = false;
		$testCases[ 'GoodButNoError' ] = array(
			$status,
			"Internal error: Status::getWikiText: Invalid result object: no error text but not OK\n",
		);

		$status = new Status();
		$status->warning( 'fooBar!' );
		$testCases[ '1StringWarning' ] = array(
			$status,
			"<fooBar!>",
		);

		$status = new Status();
		$status->warning( 'fooBar!' );
		$status->warning( 'fooBar2!' );
		$testCases[ '2StringWarnings' ] = array(
			$status,
			"* <fooBar!>\n* <fooBar2!>\n",
		);

		$status = new Status();
		$status->warning( new Message( 'fooBar!', array( 'foo', 'bar' )  ) );
		$testCases[ '1MessageWarning' ] = array(
			$status,
			"<fooBar!>",
		);

		$status = new Status();
		$status->warning( new Message( 'fooBar!', array( 'foo', 'bar' ) ) );
		$status->warning( new Message( 'fooBar2!' ) );
		$testCases[ '2MessageWarnings' ] = array(
			$status,
			"* <fooBar!>\n* <fooBar2!>\n",
		);

		return $testCases;
	}

	//todo test getMessage
	//todo test getErrorMessage
	//todo test getHTML
	//todo test getErrorMessageArray
	//todo test getStatusArray
	//todo test getErrorsByType
	//todo test replaceMessage

}
