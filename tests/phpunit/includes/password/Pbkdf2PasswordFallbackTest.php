<?php


/**
 * @group large
 * @covers Pbkdf2Password
 */
class Pbkdf2PasswordFallbackTest extends PasswordTestCase {
	protected function getTypeConfigs() {
		return [
			'pbkdf2' => [
				'class' => Pbkdf2Password::class,
				'algo' => 'sha256',
				'cost' => '10000',
				'length' => '128',
				'use-hash-extension' => false,
			],
		];
	}

	public static function providePasswordTests() {
		return [
			[ true, ":pbkdf2:sha1:1:20:c2FsdA==:DGDID5YfDnHzqbUkr2ASBi/gN6Y=", 'password' ],
			[ true, ":pbkdf2:sha1:2:20:c2FsdA==:6mwBTcctb4zNHtkqzh1B8NjeiVc=", 'password' ],
			[ true, ":pbkdf2:sha1:4096:20:c2FsdA==:SwB5AbdlSJq+rUnZJvch0GWkKcE=", 'password' ],
			[ true, ":pbkdf2:sha1:4096:16:c2EAbHQ=:Vvpqp1VICZ3MN9fwNCXgww==", "pass\x00word" ],
		];
	}
}
