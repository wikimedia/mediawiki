<?php

class ApiUsageExceptionTest extends MediaWikiTestCase {

	public function testApiUsageExceptionAcceptsIApiMessage() {
		$status = new StatusValue();
		$expectedMessage = $this->createIApiMessage();
		$status->fatal( $expectedMessage );

		$apiUsageException = new ApiUsageException(
			$this->createApiBase(),
			$status,
			500
		);

		$message = $apiUsageException->getMessageObject();
		$this->assertEquals( $expectedMessage->getKey(), $message->getKey() );
		$this->assertEquals( $expectedMessage->getParams(), $message->getParams() );
		$this->assertEquals( $expectedMessage->getApiCode(), $message->getApiCode());
		$this->assertEquals( $expectedMessage->getApiData(), $message->getApiData());
	}

	/**
	 * @return ApiBase
	 */
	private function createApiBase() {
		/** @var ApiBase|\Prophecy\Prophecy\ObjectProphecy $apiBase */
		$apiBase = $this->prophesize( ApiBase::class );

		return $apiBase->reveal();
	}

	/**
	 * @return IApiMessage
	 */
	private function createIApiMessage() {

		/** @var IApiMessage|\Prophecy\Prophecy\ObjectProphecy $message */
		$message = $this->prophesize( IApiMessage::class );
		$message->getKey()->willReturn( 'some-message' );
		$message->getParams()->willReturn( ['some-message-param'] );
		$message->getApiCode()->willReturn( 'some-api-code' );
		$message->getApiData()->willReturn( ['some-api-data'] );

		return $message->reveal();
	}
}
