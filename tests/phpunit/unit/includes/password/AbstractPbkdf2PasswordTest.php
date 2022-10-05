<?php

/**
 * @group large
 * @covers AbstractPbkdf2Password
 */
class AbstractPbkdf2PasswordTest extends \PHPUnit\Framework\TestCase {
	public function testNewInstanceUsesSpecifiedSubclass() {
		$factory = new PasswordFactory();
		$class = get_class( $this->createStub( AbstractPbkdf2Password::class ) );
		$password = AbstractPbkdf2Password::newInstance(
			$factory,
			[
				'type' => 'pbkdf2',
				'class' => $class,
				'factory' => [ AbstractPbkdf2Password::class, 'newInstance' ],
				'algo' => 'sha256',
				'cost' => '10000',
				'length' => '128',
			]
		);
		$this->assertInstanceOf( $class, $password );
	}

	/**
	 * @requires function openssl_pbkdf2
	 */
	public function testNewInstanceUsesOpenSSLByDefault() {
		$factory = new PasswordFactory();
		$password = AbstractPbkdf2Password::newInstance(
			$factory,
			[
				'type' => 'pbkdf2',
				'factory' => [ AbstractPbkdf2Password::class, 'newInstance' ],
				'algo' => 'sha256',
				'cost' => '10000',
				'length' => '128',
			]
		);
		$this->assertInstanceOf( Pbkdf2PasswordUsingOpenSSL::class, $password );
	}
}
