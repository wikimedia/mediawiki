<?php

/**
 * @covers MWOldPassword
 * @covers ParameterizedPassword
 * @covers Password
 */
class MWOldPasswordTest extends PasswordTestCase {
	protected function getTypeConfigs() {
		return [ 'A' => [
			'class' => MWOldPassword::class,
		] ];
	}

	public static function providePasswordTests() {
		return [
			[ true, ':A:5f4dcc3b5aa765d61d8327deb882cf99', 'password' ],
			// Type-B password with incorrect type name is accepted
			[ true, ':A:salt:9842afc7cb949c440c51347ed809362f', 'password' ],
			[ false, ':A:d529e941509eb9e9b9cfaeae1fe7ca23', 'password' ],
			[ false, ':A:salt:d529e941509eb9e9b9cfaeae1fe7ca23', 'password' ],
		];
	}
}
