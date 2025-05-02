<?php

use MediaWiki\Password\MWOldPassword;

/**
 * @covers \MediaWiki\Password\MWOldPassword
 * @covers \MediaWiki\Password\ParameterizedPassword
 * @covers \MediaWiki\Password\Password
 */
class MWOldPasswordTest extends PasswordTestCase {
	protected static function getTypeConfigs() {
		return [ 'A' => [
			'class' => MWOldPassword::class,
		] ];
	}

	public static function providePasswordTests() {
		return [
			[ true, ':A:5f4dcc3b5aa765d61d8327deb882cf99', 'password' ],
			[ false, ':A:d529e941509eb9e9b9cfaeae1fe7ca23', 'password' ],
		];
	}
}
