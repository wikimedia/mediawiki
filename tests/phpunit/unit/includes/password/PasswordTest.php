<?php
/**
 * Testing framework for the Password infrastructure
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Password\MWOldPassword;
use MediaWiki\Password\Password;
use MediaWiki\Password\PasswordError;
use MediaWiki\Password\PasswordFactory;

/**
 * @covers \MediaWiki\Password\Password
 */
class PasswordTest extends MediaWikiUnitTestCase {
	public function testNoTypeThrows() {
		$factory = new PasswordFactory();
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Password configuration must contain a type name' );
		$this->getMockBuilder( Password::class )
			->setConstructorArgs( [ $factory, [] ] )
			->getMock();
	}

	public function testGetType() {
		$factory = new PasswordFactory();
		$password = new MWOldPassword( $factory, [ 'type' => 'this is a test' ] );
		$this->assertSame( 'this is a test', $password->getType() );
	}

	public function testToStringThrows() {
		$factory = new PasswordFactory();
		$password = new MWOldPassword( $factory, [ 'type' => 'B' ], str_repeat( 'X', 300 ) );
		$this->expectException( PasswordError::class );
		$this->expectExceptionMessage( 'Password hash is too big' );
		$password->toString();
	}

	public function testToString() {
		$factory = new PasswordFactory();
		$password = $this->getMockBuilder( Password::class )
			->setConstructorArgs( [ $factory, [ 'type' => 'X' ], ':X:foo' ] )
			->getMockForAbstractClass();
		$this->assertSame( ':X:foo', $password->toString() );
	}
}
