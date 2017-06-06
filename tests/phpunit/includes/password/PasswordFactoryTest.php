<?php

/**
 * @covers PasswordFactory
 */
class PasswordFactoryTest extends MediaWikiTestCase {
	public function testRegister() {
		$pf = new PasswordFactory;
		$pf->register( 'foo', [ 'class' => 'InvalidPassword' ] );
		$this->assertArrayHasKey( 'foo', $pf->getTypes() );
	}

	public function testSetDefaultType() {
		$pf = new PasswordFactory;
		$pf->register( '1', [ 'class' => 'InvalidPassword' ] );
		$pf->register( '2', [ 'class' => 'InvalidPassword' ] );
		$pf->setDefaultType( '1' );
		$this->assertSame( '1', $pf->getDefaultType() );
		$pf->setDefaultType( '2' );
		$this->assertSame( '2', $pf->getDefaultType() );
	}

	/**
	 * @expectedException Exception
	 */
	public function testSetDefaultTypeError() {
		$pf = new PasswordFactory;
		$pf->setDefaultType( 'bogus' );
	}

	public function testInit() {
		$config = new HashConfig( [
			'PasswordConfig' => [
				'foo' => [ 'class' => 'InvalidPassword' ],
			],
			'PasswordDefault' => 'foo'
		] );
		$pf = new PasswordFactory;
		$pf->init( $config );
		$this->assertSame( 'foo', $pf->getDefaultType() );
		$this->assertArrayHasKey( 'foo', $pf->getTypes() );
	}

	public function testNewFromCiphertext() {
		$pf = new PasswordFactory;
		$pf->register( 'B', [ 'class' => 'MWSaltedPassword' ] );
		$pw = $pf->newFromCiphertext( ':B:salt:d529e941509eb9e9b9cfaeae1fe7ca23' );
		$this->assertInstanceOf( MWSaltedPassword::class, $pw );
	}

	public function provideNewFromCiphertextErrors() {
		return [ [ 'blah' ], [ ':blah:' ] ];
	}

	/**
	 * @dataProvider provideNewFromCiphertextErrors
	 * @expectedException PasswordError
	 */
	public function testNewFromCiphertextErrors( $hash ) {
		$pf = new PasswordFactory;
		$pf->register( 'B', [ 'class' => 'MWSaltedPassword' ] );
		$pf->newFromCiphertext( $hash );
	}

	public function testNewFromType() {
		$pf = new PasswordFactory;
		$pf->register( 'B', [ 'class' => 'MWSaltedPassword' ] );
		$pw = $pf->newFromType( 'B' );
		$this->assertInstanceOf( MWSaltedPassword::class, $pw );
	}

	/**
	 * @expectedException PasswordError
	 */
	public function testNewFromTypeError() {
		$pf = new PasswordFactory;
		$pf->register( 'B', [ 'class' => 'MWSaltedPassword' ] );
		$pf->newFromType( 'bogus' );
	}

	public function testNewFromPlaintext() {
		$pf = new PasswordFactory;
		$pf->register( 'A', [ 'class' => 'MWOldPassword' ] );
		$pf->register( 'B', [ 'class' => 'MWSaltedPassword' ] );
		$pf->setDefaultType( 'A' );

		$this->assertInstanceOf( 'InvalidPassword', $pf->newFromPlaintext( null ) );
		$this->assertInstanceOf( 'MWOldPassword', $pf->newFromPlaintext( 'password' ) );
		$this->assertInstanceOf( 'MWSaltedPassword',
			$pf->newFromPlaintext( 'password', $pf->newFromType( 'B' ) ) );
	}

	public function testNeedsUpdate() {
		$pf = new PasswordFactory;
		$pf->register( 'A', [ 'class' => 'MWOldPassword' ] );
		$pf->register( 'B', [ 'class' => 'MWSaltedPassword' ] );
		$pf->setDefaultType( 'A' );

		$this->assertFalse( $pf->needsUpdate( $pf->newFromType( 'A' ) ) );
		$this->assertTrue( $pf->needsUpdate( $pf->newFromType( 'B' ) ) );
	}

	public function testGenerateRandomPasswordString() {
		$this->assertSame( 13, strlen( PasswordFactory::generateRandomPasswordString( 13 ) ) );
	}

	public function testNewInvalidPassword() {
		$this->assertInstanceOf( 'InvalidPassword', PasswordFactory::newInvalidPassword() );
	}
}
