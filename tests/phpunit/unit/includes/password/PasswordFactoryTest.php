<?php

use MediaWiki\MainConfigNames;

/**
 * @covers PasswordFactory
 */
class PasswordFactoryTest extends MediaWikiUnitTestCase {
	public function testConstruct() {
		$pf = new PasswordFactory();
		$this->assertEquals( [ '' ], array_keys( $pf->getTypes() ) );
		$this->assertSame( '', $pf->getDefaultType() );

		$pf = new PasswordFactory( [
			'foo' => [ 'class' => 'FooPassword' ],
			'bar' => [ 'class' => 'BarPassword', 'baz' => 'boom' ],
		], 'foo' );
		$this->assertEquals( [ '', 'foo', 'bar' ], array_keys( $pf->getTypes() ) );
		$bar = $pf->getTypes()['bar'];
		$expected = [ 'class' => 'BarPassword', 'baz' => 'boom' ];
		$this->assertArrayEquals( $expected, array_intersect( $bar, $expected ) );
		$this->assertEquals( 'foo', $pf->getDefaultType() );
	}

	public function testRegister() {
		$pf = new PasswordFactory;
		$pf->register( 'foo', [ 'class' => InvalidPassword::class ] );
		$this->assertArrayHasKey( 'foo', $pf->getTypes() );
	}

	public function testSetDefaultType() {
		$pf = new PasswordFactory;
		$pf->register( '1', [ 'class' => InvalidPassword::class ] );
		$pf->register( '2', [ 'class' => InvalidPassword::class ] );
		$pf->setDefaultType( '1' );
		$this->assertSame( '1', $pf->getDefaultType() );
		$pf->setDefaultType( '2' );
		$this->assertSame( '2', $pf->getDefaultType() );
	}

	public function testSetDefaultTypeError() {
		$pf = new PasswordFactory;
		$this->expectException( Exception::class );
		$pf->setDefaultType( 'bogus' );
	}

	public function testInit() {
		$config = new HashConfig( [
			MainConfigNames::PasswordConfig => [
				'foo' => [ 'class' => InvalidPassword::class ],
			],
			MainConfigNames::PasswordDefault => 'foo'
		] );
		$pf = new PasswordFactory;
		$pf->init( $config );
		$this->assertSame( 'foo', $pf->getDefaultType() );
		$this->assertArrayHasKey( 'foo', $pf->getTypes() );
	}

	public function testNewFromCiphertext() {
		$pf = new PasswordFactory;
		$pf->register( 'B', [ 'class' => MWSaltedPassword::class ] );
		$pw = $pf->newFromCiphertext( ':B:salt:d529e941509eb9e9b9cfaeae1fe7ca23' );
		$this->assertInstanceOf( MWSaltedPassword::class, $pw );
	}

	public function provideNewFromCiphertextErrors() {
		return [ [ 'blah' ], [ ':blah:' ] ];
	}

	/**
	 * @dataProvider provideNewFromCiphertextErrors
	 */
	public function testNewFromCiphertextErrors( $hash ) {
		$pf = new PasswordFactory;
		$pf->register( 'B', [ 'class' => MWSaltedPassword::class ] );
		$this->expectException( PasswordError::class );
		$pf->newFromCiphertext( $hash );
	}

	public function testNewFromType() {
		$pf = new PasswordFactory;
		$pf->register( 'B', [ 'class' => MWSaltedPassword::class ] );
		$pw = $pf->newFromType( 'B' );
		$this->assertInstanceOf( MWSaltedPassword::class, $pw );
	}

	public function testNewFromTypeError() {
		$pf = new PasswordFactory;
		$pf->register( 'B', [ 'class' => MWSaltedPassword::class ] );
		$this->expectException( PasswordError::class );
		$pf->newFromType( 'bogus' );
	}

	public function testNewFromPlaintext() {
		$pf = new PasswordFactory;
		$pf->register( 'A', [ 'class' => MWOldPassword::class ] );
		$pf->register( 'B', [ 'class' => MWSaltedPassword::class ] );
		$pf->setDefaultType( 'A' );

		$this->assertInstanceOf( InvalidPassword::class, $pf->newFromPlaintext( null ) );
		$this->assertInstanceOf( MWOldPassword::class, $pf->newFromPlaintext( 'password' ) );
		$this->assertInstanceOf( MWSaltedPassword::class,
			$pf->newFromPlaintext( 'password', $pf->newFromType( 'B' ) ) );
	}

	public function testNeedsUpdate() {
		$pf = new PasswordFactory;
		$pf->register( 'A', [ 'class' => MWOldPassword::class ] );
		$pf->register( 'B', [ 'class' => MWSaltedPassword::class ] );
		$pf->setDefaultType( 'A' );

		$this->assertFalse( $pf->needsUpdate( $pf->newFromType( 'A' ) ) );
		$this->assertTrue( $pf->needsUpdate( $pf->newFromType( 'B' ) ) );
	}

	public function testGenerateRandomPasswordString() {
		$this->assertSame( 13, strlen( PasswordFactory::generateRandomPasswordString( 13 ) ) );
	}

	public function testNewInvalidPassword() {
		$this->assertInstanceOf( InvalidPassword::class, PasswordFactory::newInvalidPassword() );
	}
}
