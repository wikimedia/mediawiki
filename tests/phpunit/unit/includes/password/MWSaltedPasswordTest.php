<?php

use MediaWiki\Password\MWSaltedPassword;

/**
 * @covers \MediaWiki\Password\MWSaltedPassword
 * @covers \MediaWiki\Password\ParameterizedPassword
 * @covers \MediaWiki\Password\Password
 */
class MWSaltedPasswordTest extends PasswordTestCase {
	protected static function getTypeConfigs() {
		return [ 'B' => [
			'class' => MWSaltedPassword::class,
		] ];
	}

	public static function providePasswordTests() {
		return [
			[ true, ':B:salt:9842afc7cb949c440c51347ed809362f', 'password' ],
			[ false, ':B:salt:d529e941509eb9e9b9cfaeae1fe7ca23', 'password' ],
		];
	}
}
