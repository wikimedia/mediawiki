<?php

class ApiUsageExceptionTest extends MediaWikiTestCase {

	public function testApiUsageExceptionAcceptsMessageSpecifier() {
		$expectedMessage = $this->createMessageSpecifier();
		$status = Status::newFatal( $expectedMessage );

		$apiUsageException = new ApiUsageException(
			null,
			$status,
			500
		);

		$message = $apiUsageException->getMessageObject();
		$this->assertEquals( $expectedMessage->getKey(), $message->getKey() );
		$this->assertEquals( $expectedMessage->getParams(), $message->getParams() );
	}

	public function testApiUsageExceptionAcceptsIApiMessage() {
		$expectedMessage = $this->createIApiMessage();
		$status = Status::newFatal( $expectedMessage );

		$apiUsageException = new ApiUsageException(
			null,
			$status,
			500
		);

		$message = $apiUsageException->getMessageObject();
		$this->assertEquals( $expectedMessage->getKey(), $message->getKey() );
		$this->assertEquals( $expectedMessage->getParams(), $message->getParams() );
	}

	public function testApiUsageExceptionAcceptsStatusValue() {
		$this->markTestSkipped(
			'Currently ApiUsageException cannot accept StatusValue. Should be fixed'
		);
		$expectedMessage = $this->createMessageSpecifier();
		$status = StatusValue::newFatal( $expectedMessage );

		$apiUsageException = new ApiUsageException(
			null,
			$status,
			500
		);

		$message = $apiUsageException->getMessageObject();
		$this->assertEquals( $expectedMessage->getKey(), $message->getKey() );
		$this->assertEquals( $expectedMessage->getParams(), $message->getParams() );
	}

	/**
	 * @return MessageSpecifier
	 */
	private function createMessageSpecifier() {
		/** @var MessageSpecifier|\Prophecy\Prophecy\ObjectProphecy $message */
		$message = $this->prophesize( MessageSpecifier::class );
		$message->getKey()->willReturn( 'some-MessageSpecifier-key' );
		$message->getParams()->willReturn( [ 'some-MessageSpecifier-param' ] );

		return $message->reveal();
	}

	/**
	 * @return IApiMessage
	 */
	private function createIApiMessage() {
		/** @var IApiMessage|\Prophecy\Prophecy\ObjectProphecy $message */
		$message = $this->prophesize( IApiMessage::class );
		$message->getKey()->willReturn( 'some-IApiMessage-key' );
		$message->getParams()->willReturn( [ 'some-IApiMessage-param' ] );
		$message->getApiCode()->willReturn( 'some-api-code' );
		$message->getApiData()->willReturn( [ 'some-api-data' ] );

		return $message->reveal();
	}
}
