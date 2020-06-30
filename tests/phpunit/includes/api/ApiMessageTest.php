<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 */
class ApiMessageTest extends MediaWikiIntegrationTestCase {

	private function compareMessages( Message $msg, Message $msg2 ) {
		$this->assertSame( $msg->getKey(), $msg2->getKey(), 'getKey' );
		$this->assertSame( $msg->getKeysToTry(), $msg2->getKeysToTry(), 'getKeysToTry' );
		$this->assertSame( $msg->getParams(), $msg2->getParams(), 'getParams' );
		$this->assertSame( $msg->getLanguage(), $msg2->getLanguage(), 'getLanguage' );

		$msg = TestingAccessWrapper::newFromObject( $msg );
		$msg2 = TestingAccessWrapper::newFromObject( $msg2 );
		$this->assertSame( $msg->interface, $msg2->interface, 'interface' );
		$this->assertSame( $msg->useDatabase, $msg2->useDatabase, 'useDatabase' );
		$this->assertSame( $msg->format, $msg2->format, 'format' );
		$this->assertSame(
			$msg->title ? $msg->title->getFullText() : null,
			$msg2->title ? $msg2->title->getFullText() : null,
			'title'
		);
	}

	/**
	 * @covers ApiMessageTrait
	 */
	public function testCodeDefaults() {
		$msg = new ApiMessage( 'foo' );
		$this->assertSame( 'foo', $msg->getApiCode() );

		$msg = new ApiMessage( 'apierror-bar' );
		$this->assertSame( 'bar', $msg->getApiCode() );

		$msg = new ApiMessage( 'apiwarn-baz' );
		$this->assertSame( 'baz', $msg->getApiCode() );

		// Weird "message key"
		$msg = new ApiMessage( "<foo> bar\nbaz" );
		$this->assertSame( '_foo__bar_baz', $msg->getApiCode() );

		// BC case
		$msg = new ApiMessage( 'actionthrottledtext' );
		$this->assertSame( 'ratelimited', $msg->getApiCode() );

		$msg = new ApiMessage( [ 'apierror-missingparam', 'param' ] );
		$this->assertSame( 'noparam', $msg->getApiCode() );
	}

	/**
	 * @covers ApiMessageTrait
	 * @dataProvider provideInvalidCode
	 * @param mixed $code
	 */
	public function testInvalidCode( $code ) {
		$msg = new ApiMessage( 'foo' );
		try {
			$msg->setApiCode( $code );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertTrue( true );
		}

		try {
			new ApiMessage( 'foo', $code );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertTrue( true );
		}
	}

	public static function provideInvalidCode() {
		return [
			[ '' ],
			[ 42 ],
			[ 'A bad code' ],
			[ 'Project:A_page_title' ],
			[ "WTF\nnewlines" ],
		];
	}

	/**
	 * @covers ApiMessage
	 * @covers ApiMessageTrait
	 */
	public function testApiMessage() {
		$msg = new Message( [ 'foo', 'bar' ], [ 'baz' ] );
		$msg->inLanguage( 'de' )->title( Title::newMainPage() );
		$msg2 = new ApiMessage( $msg, 'code', [ 'data' ] );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );

		$msg2 = unserialize( serialize( $msg2 ) );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );

		$msg = new Message( [ 'foo', 'bar' ], [ 'baz' ] );
		$msg2 = new ApiMessage( [ [ 'foo', 'bar' ], 'baz' ], 'code', [ 'data' ] );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );

		$msg = new Message( 'foo' );
		$msg2 = new ApiMessage( 'foo' );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'foo', $msg2->getApiCode() );
		$this->assertEquals( [], $msg2->getApiData() );

		$msg2->setApiCode( 'code', [ 'data' ] );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );
		$msg2->setApiCode( null );
		$this->assertEquals( 'foo', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );
		$msg2->setApiData( [ 'data2' ] );
		$this->assertEquals( [ 'data2' ], $msg2->getApiData() );
	}

	/**
	 * @covers ApiRawMessage
	 * @covers ApiMessageTrait
	 */
	public function testApiRawMessage() {
		$msg = new RawMessage( 'foo', [ 'baz' ] );
		$msg->inLanguage( 'de' )->title( Title::newMainPage() );
		$msg2 = new ApiRawMessage( $msg, 'code', [ 'data' ] );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );

		$msg2 = unserialize( serialize( $msg2 ) );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );

		$msg = new RawMessage( 'foo', [ 'baz' ] );
		$msg2 = new ApiRawMessage( [ 'foo', 'baz' ], 'code', [ 'data' ] );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );

		$msg = new RawMessage( 'foo' );
		$msg2 = new ApiRawMessage( 'foo', 'code', [ 'data' ] );
		$this->compareMessages( $msg, $msg2 );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );

		$msg2->setApiCode( 'code', [ 'data' ] );
		$this->assertEquals( 'code', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );
		$msg2->setApiCode( null );
		$this->assertEquals( 'foo', $msg2->getApiCode() );
		$this->assertEquals( [ 'data' ], $msg2->getApiData() );
		$msg2->setApiData( [ 'data2' ] );
		$this->assertEquals( [ 'data2' ], $msg2->getApiData() );
	}

	/**
	 * @covers ApiMessage::create
	 */
	public function testApiMessageCreate() {
		$this->assertInstanceOf( ApiMessage::class, ApiMessage::create( new Message( 'mainpage' ) ) );
		$this->assertInstanceOf(
			ApiRawMessage::class, ApiMessage::create( new RawMessage( 'mainpage' ) )
		);
		$this->assertInstanceOf( ApiMessage::class, ApiMessage::create( 'mainpage' ) );

		$msg = new ApiMessage( [ 'parentheses', 'foobar' ] );
		$msg2 = new Message( 'parentheses', [ 'foobar' ] );

		$this->assertSame( $msg, ApiMessage::create( $msg ) );
		$this->assertEquals( $msg, ApiMessage::create( $msg2 ) );
		$this->assertEquals( $msg, ApiMessage::create( [ 'parentheses', 'foobar' ] ) );
		$this->assertEquals( $msg,
			ApiMessage::create( [ 'message' => 'parentheses', 'params' => [ 'foobar' ] ] )
		);
		$this->assertSame( $msg,
			ApiMessage::create( [ 'message' => $msg, 'params' => [ 'xxx' ] ] )
		);
		$this->assertEquals( $msg,
			ApiMessage::create( [ 'message' => $msg2, 'params' => [ 'xxx' ] ] )
		);
		$this->assertSame( $msg,
			ApiMessage::create( [ 'message' => $msg ] )
		);

		$msg = new ApiRawMessage( [ 'parentheses', 'foobar' ] );
		$this->assertSame( $msg, ApiMessage::create( $msg ) );
	}

}
