<?php

/**
 * @author Adam Shorland
 */
class StatusTest extends MediaWikiTestCase {

	public function testCanConstruct(){
		new Status();
		$this->assertTrue( true );
	}

	/**
	 * @dataProvider provideValues
	 * @covers Status::newGood
	 * @covers Status::getValue
	 * @covers Status::isGood
	 * @covers Status::isOK
	 */
	public function testNewGood( $value = null ){
		$status = Status::newGood( $value );
		$this->assertTrue( $status->isGood() );
		$this->assertTrue( $status->isOK() );
		$this->assertEquals( $value, $status->getValue() );
	}

	public static function provideValues(){
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
	 * @covers Status::isGood
	 * @covers Status::isOK
	 * @covers Status::getMessage
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
	 * @covers Status::isGood
	 * @covers Status::isOK
	 * @covers Status::getMessage
	 */
	public function testNewFatalWithString() {
		$message = 'foo';
		$status = Status::newFatal( $message );
		$this->assertFalse( $status->isGood() );
		$this->assertFalse( $status->isOK() );
		$newMessage = $status->getMessage();
		$this->assertEquals( $message, $newMessage->getKey() );
	}

	/**
	 * @dataProvider provideSetResult
	 * @covers Status::getValue
	 * @covers Status::isOK
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
	 * @dataProvider provideMockMessageDetails
	 * @covers Status::warning
	 * @covers Status::getWarningsArray
	 */
	public function testWarningWithMessage( $mockDetails ) {
		$status = new Status();
		$messages = $this->getMockMessages( $mockDetails );

		foreach( $messages as $message ){
			$status->warning( $message );
		}
		$warnings = $status->getWarningsArray();

		$this->assertEquals( count( $messages ), count( $warnings ) );
		foreach( $messages as $key => $message ) {
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

		foreach( $messages as $message ){
			$status->error( $message );
		}
		$errors = $status->getErrorsArray();

		$this->assertEquals( count( $messages ), count( $errors ) );
		foreach( $messages as $key => $message ) {
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
	protected function getMockMessages( $messageDetails ){
		$messages = array();
		foreach( $messageDetails as $key => $paramsArray ){
			$messages[] = $this->getMockMessage( $key, $paramsArray );
		}
		return $messages;
	}

	public static function provideMockMessageDetails(){
		return array(
			array( array( 'key1' => array( 'foo' => 'bar' ) ) ),
			array( array( 'key1' => array( 'foo' => 'bar' ), 'key2' => array( 'foo2' => 'bar2' ) ) ),
		);
	}

	/**
	 * @covers Status::merge
	 * @todo test merge with $overwriteValue true
	 */
	public function testMerge(){
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

	//todo test cleanParams
	//todo test getWikiText
	//todo test getMessage
	//todo test getErrorMessage
	//todo test getHTML
	//todo test getErrorMessageArray
	//todo test getStatusArray
	//todo test getErrorsByType
	//todo test replaceMessage
	//todo test replaceMessage

}
