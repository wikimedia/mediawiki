<?php

/**
 * @group large
 * @covers Pbkdf2Password
 * @covers Password
 * @covers ParameterizedPassword
 * @requires function hash_pbkdf2
 */
class Pbkdf2PasswordTest extends PasswordTestCase {
	protected function getTypeConfigs() {
		return [ 'pbkdf2' => [
			'class' => Pbkdf2Password::class,
			'algo' => 'sha256',
			'cost' => '10000',
			'length' => '128',
		] ];
	}

	public static function providePasswordTests() {
		return [
			[ true, ":pbkdf2:sha1:1:20:c2FsdA==:DGDID5YfDnHzqbUkr2ASBi/gN6Y=", 'password' ],
			[ true, ":pbkdf2:sha1:2:20:c2FsdA==:6mwBTcctb4zNHtkqzh1B8NjeiVc=", 'password' ],
			[ true, ":pbkdf2:sha1:4096:20:c2FsdA==:SwB5AbdlSJq+rUnZJvch0GWkKcE=", 'password' ],
			[ true, ":pbkdf2:sha1:4096:16:c2EAbHQ=:Vvpqp1VICZ3MN9fwNCXgww==", "pass\x00word" ],
		];
	}

	public function testCryptThrows() {
		$factory = new PasswordFactory();
		$password = new Pbkdf2Password(
			$factory,
			[
				'type' => 'pbkdf2',
				'algo' => 'fail',
				'cost' => '10000',
				'length' => '128',
			]
		);
		$this->expectException( PasswordError::class );
		$this->expectExceptionMessage( 'Error when hashing password.' );
		@$password->crypt( 'whatever' );
	}
}
