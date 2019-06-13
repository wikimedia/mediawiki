<?php

/**
 * @covers ApiUsageException
 */
class ApiUsageExceptionTest extends MediaWikiTestCase {

	public function testCreateWithStatusValue_CanGetAMessageObject() {
		$messageKey = 'some-message-key';
		$messageParameter = 'some-parameter';
		$statusValue = new StatusValue();
		$statusValue->fatal( $messageKey, $messageParameter );

		$apiUsageException = new ApiUsageException( null, $statusValue );
		/** @var \Message $gotMessage */
		$gotMessage = $apiUsageException->getMessageObject();

		$this->assertInstanceOf( \Message::class, $gotMessage );
		$this->assertEquals( $messageKey, $gotMessage->getKey() );
		$this->assertEquals( [ $messageParameter ], $gotMessage->getParams() );
	}

	public function testNewWithMessage_ThenGetMessageObject_ReturnsApiMessageWithProvidedData() {
		$expectedMessage = new Message( 'some-message-key', [ 'some message parameter' ] );
		$expectedCode = 'some-error-code';
		$expectedData = [ 'some-error-data' ];

		$apiUsageException = ApiUsageException::newWithMessage(
			null,
			$expectedMessage,
			$expectedCode,
			$expectedData
		);
		/** @var \ApiMessage $gotMessage */
		$gotMessage = $apiUsageException->getMessageObject();

		$this->assertInstanceOf( \ApiMessage::class, $gotMessage );
		$this->assertEquals( $expectedMessage->getKey(), $gotMessage->getKey() );
		$this->assertEquals( $expectedMessage->getParams(), $gotMessage->getParams() );
		$this->assertEquals( $expectedCode, $gotMessage->getApiCode() );
		$this->assertEquals( $expectedData, $gotMessage->getApiData() );
	}

}
