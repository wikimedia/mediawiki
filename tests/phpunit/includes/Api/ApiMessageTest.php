<?php

namespace MediaWiki\Tests\Api;

use InvalidArgumentException;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\ApiRawMessage;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageReferenceValue;
use MediaWikiIntegrationTestCase;
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
		$this->assertSame( $msg->isInterface, $msg2->isInterface, 'interface' );
		$this->assertSame( $msg->useDatabase, $msg2->useDatabase, 'useDatabase' );
		$this->assertSame(
			$msg->contextPage ? "{$msg->contextPage->getNamespace()}:{$msg->contextPage->getDbKey()}" : null,
			$msg2->contextPage ? "{$msg->contextPage->getNamespace()}:{$msg->contextPage->getDbKey()}" : null,
			'title'
		);
	}

	/**
	 * @covers \MediaWiki\Api\ApiMessageTrait
	 * @dataProvider provideCodeDefaults
	 */
	public function testCodeDefaults( $msg, $expectedCode ) {
		$apiMessage = new ApiMessage( $msg );
		$this->assertSame( $expectedCode, $apiMessage->getApiCode() );
	}

	public static function provideCodeDefaults() {
		// $msg, $expectedCode
		yield 'foo' => [ 'foo', 'foo' ];
		yield 'apierror prefix' => [ 'apierror-bar', 'bar' ];
		yield 'apiwarn prefix' => [ 'apiwarn-baz', 'baz' ];
		yield 'Weird "message key"' => [ "<foo> bar\nbaz", '_foo__bar_baz' ];
		yield 'BC string' => [ 'actionthrottledtext', 'ratelimited' ];
		yield 'array' => [ [ 'apierror-missingparam', 'param' ], 'noparam' ];
	}

	/**
	 * @covers \MediaWiki\Api\ApiMessageTrait
	 * @dataProvider provideInvalidCode
	 */
	public function testInvalidCode( $code ) {
		$msg = new ApiMessage( 'foo' );
		try {
			$msg->setApiCode( $code );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException ) {
			$this->assertTrue( true );
		}

		try {
			new ApiMessage( 'foo', $code );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException ) {
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
	 * @covers \MediaWiki\Api\ApiMessage
	 * @covers \MediaWiki\Api\ApiMessageTrait
	 */
	public function testApiMessage() {
		$msg = new Message( [ 'foo', 'bar' ], [ 'baz' ] );
		$msg->inLanguage( 'de' )
			->page(
				PageReferenceValue::localReference( NS_MAIN, 'Main_Page' )
			);
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
	 * @covers \MediaWiki\Api\ApiRawMessage
	 * @covers \MediaWiki\Api\ApiMessageTrait
	 */
	public function testApiRawMessage() {
		$msg = new RawMessage( 'foo', [ 'baz' ] );
		$msg->inLanguage( 'de' )->page(
			PageReferenceValue::localReference( NS_MAIN, 'Main_Page' )
		);
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
	 * @covers \MediaWiki\Api\ApiMessage::create
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
