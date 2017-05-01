<?php

namespace MediaWiki\Session;

use MediaWikiTestCase;

/**
 * @group Session
 * @covers MediaWiki\Session\Token
 */
class TokenTest extends MediaWikiTestCase {

	public function testBasics() {
		$token = $this->getMockBuilder( Token::class )
			->setMethods( [ 'toStringAtTimestamp' ] )
			->setConstructorArgs( [ 'sekret', 'salty', true ] )
			->getMock();
		$token->expects( $this->any() )->method( 'toStringAtTimestamp' )
			->will( $this->returnValue( 'faketoken+\\' ) );

		$this->assertSame( 'faketoken+\\', $token->toString() );
		$this->assertSame( 'faketoken+\\', (string)$token );
		$this->assertTrue( $token->wasNew() );

		$token = new Token( 'sekret', 'salty', false );
		$this->assertFalse( $token->wasNew() );
	}

	public function testToStringAtTimestamp() {
		$token = \TestingAccessWrapper::newFromObject( new Token( 'sekret', 'salty', false ) );

		$this->assertSame(
			'd9ade0c7d4349e9df9094e61c33a5a0d5644fde2+\\',
			$token->toStringAtTimestamp( 1447362018 )
		);
		$this->assertSame(
			'ee2f7a2488dea9176c224cfb400d43be5644fdea+\\',
			$token->toStringAtTimestamp( 1447362026 )
		);
	}

	public function testGetTimestamp() {
		$this->assertSame(
			1447362018, Token::getTimestamp( 'd9ade0c7d4349e9df9094e61c33a5a0d5644fde2+\\' )
		);
		$this->assertSame(
			1447362026, Token::getTimestamp( 'ee2f7a2488dea9176c224cfb400d43be5644fdea+\\' )
		);
		$this->assertNull( Token::getTimestamp( 'ee2f7a2488dea9176c224cfb400d43be5644fdea-\\' ) );
		$this->assertNull( Token::getTimestamp( 'ee2f7a2488dea9176c224cfb400d43be+\\' ) );

		$this->assertNull( Token::getTimestamp( 'ee2f7a2488dea9x76c224cfb400d43be5644fdea+\\' ) );
	}

	public function testMatch() {
		$token = \TestingAccessWrapper::newFromObject( new Token( 'sekret', 'salty', false ) );

		$test = $token->toStringAtTimestamp( time() - 10 );
		$this->assertTrue( $token->match( $test ) );
		$this->assertTrue( $token->match( $test, 12 ) );
		$this->assertFalse( $token->match( $test, 8 ) );

		$this->assertFalse( $token->match( 'ee2f7a2488dea9176c224cfb400d43be5644fdea-\\' ) );
	}

}
