<?php

class ApiUsageExceptionTest extends MediaWikiTestCase {

	public function testGetMessageObject_ReturnsApiMessageWithProvidedData() {
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
