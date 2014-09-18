<?php

/**
 * @group large
 */
class Pbkdf2PasswordTest extends PasswordTestCase {
	protected function getTypeConfigs() {
		return array( 'pbkdf2' => array(
			'class' => 'Pbkdf2Password',
			'algo' => 'sha256',
			'cost' => '10000',
			'length' => '128',
		) );
	}

	public static function providePasswordTests() {
		return array(
			array( true, ":pbkdf2:sha1:1:20:c2FsdA==:DGDID5YfDnHzqbUkr2ASBi/gN6Y=", 'password' ),
			array( true, ":pbkdf2:sha1:2:20:c2FsdA==:6mwBTcctb4zNHtkqzh1B8NjeiVc=", 'password' ),
			array( true, ":pbkdf2:sha1:4096:20:c2FsdA==:SwB5AbdlSJq+rUnZJvch0GWkKcE=", 'password' ),
			array( true, ":pbkdf2:sha1:4096:16:c2EAbHQ=:Vvpqp1VICZ3MN9fwNCXgww==", "pass\x00word" ),
		);
	}
}
