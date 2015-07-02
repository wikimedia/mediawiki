<?php

/**
 * @group API
 */
class ApiMessageTest extends MediaWikiTestCase {

	private function compareMessages( $msg, $msg2 ) {
		$this->assertSame( $msg->getKey(), $msg2->getKey(), 'getKey' );
		$this->assertSame( $msg->getKeysToTry(), $msg2->getKeysToTry(), 'getKeysToTry' );
		$this->assertSame( $msg->getParams(), $msg2->getParams(), 'getParams' );
		$this->assertSame( $msg->getFormat(), $msg2->getFormat(), 'getFormat' );
		$this->assertSame( $msg->getLanguage(), $msg2->getLanguage(), 'getLanguage' );

		$msg = TestingAccessWrapper::newFromObject( $msg );
		$msg2 = TestingAccessWrapper::newFromObject( $msg2 );
		$this->assertSame( $msg->interface, $msg2->interface, 'interface' );
		$this->assertSame( $msg->useDatabase, $msg2->useDatabase, 'useDatabase' );
		$this->assertSame(
			$msg->title ? $msg->title->getFullText() : null,
			$msg2->title ? $msg2->title->getFullText() : null,
			'title'
		);
	}

	/**
	 * @covers ApiMessage
	 */
	public function testApiMessage() {
		$msg = new Message( array( 'foo', 'bar' ), array( 'baz' ) );
		$msg->inLanguage( 'de' )->title( Title::newMainPage() );
		$msg2 = new ApiMessage( $msg, 'code', array( 'data' ) );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );

		$msg2 = unserialize( serialize( $msg2 ) );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );

		$msg = new Message( array( 'foo', 'bar' ), array( 'baz' ) );
		$msg2 = new ApiMessage( array( array( 'foo', 'bar' ), 'baz' ), 'code', array( 'data' ) );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );

		$msg = new Message( 'foo' );
		$msg2 = new ApiMessage( 'foo' );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'foo', $msg2->getApiCode() );
		$this->assertEquals( array(), $msg2->getApiData() );

		$msg2->setApiCode( 'code', array( 'data' ) );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );
		$msg2->setApiCode( null );
		$this->assertEquals( 'foo', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );
		$msg2->setApiData( array( 'data2' ) );
		$this->assertEquals( array( 'data2' ), $msg2->getApiData() );
	}

	/**
	 * @covers ApiRawMessage
	 */
	public function testApiRawMessage() {
		$msg = new RawMessage( 'foo', array( 'baz' ) );
		$msg->inLanguage( 'de' )->title( Title::newMainPage() );
		$msg2 = new ApiRawMessage( $msg, 'code', array( 'data' ) );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );

		$msg2 = unserialize( serialize( $msg2 ) );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );

		$msg = new RawMessage( 'foo', array( 'baz' ) );
		$msg2 = new ApiRawMessage( array( 'foo', 'baz' ), 'code', array( 'data' ) );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );

		$msg = new RawMessage( 'foo' );
		$msg2 = new ApiRawMessage( 'foo', 'code', array( 'data' ) );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );

		$msg2->setApiCode( 'code', array( 'data' ) );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );
		$msg2->setApiCode( null );
		$this->assertEquals( 'foo', $msg2->getApiCode() );
		$this->assertEquals( array( 'data' ), $msg2->getApiData() );
		$msg2->setApiData( array( 'data2' ) );
		$this->assertEquals( array( 'data2' ), $msg2->getApiData() );
	}

	/**
	 * @covers ApiMessage::create
	 */
	public function testApiMessageCreate() {
		$this->assertInstanceOf( 'ApiMessage', ApiMessage::create( new Message( 'mainpage' ) ) );
		$this->assertInstanceOf( 'ApiRawMessage', ApiMessage::create( new RawMessage( 'mainpage' ) ) );
		$this->assertInstanceOf( 'ApiMessage', ApiMessage::create( 'mainpage' ) );

		$msg = new ApiMessage( 'mainpage' );
		$this->assertSame( $msg, ApiMessage::create( $msg ) );

		$msg = new ApiRawMessage( 'mainpage' );
		$this->assertSame( $msg, ApiMessage::create( $msg ) );
	}

}
