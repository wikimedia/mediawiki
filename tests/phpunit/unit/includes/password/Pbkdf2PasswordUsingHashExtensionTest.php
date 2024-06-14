<?php

use MediaWiki\Password\Pbkdf2PasswordUsingHashExtension;

/**
 * @group large
 * @covers \Mediawiki\Password\AbstractPbkdf2Password
 * @covers \Mediawiki\Password\Pbkdf2PasswordUsingHashExtension
 */
class Pbkdf2PasswordUsingHashExtensionTest extends Pbkdf2PasswordTestCase {
	protected static function getPbkdf2PasswordClass() {
		return Pbkdf2PasswordUsingHashExtension::class;
	}

	public static function providePasswordTests() {
		return array_merge(
			parent::providePasswordTests(),
			[
				[ true, ":pbkdf2:sha1:1:20:c2FsdA==:DGDID5YfDnHzqbUkr2ASBi/gN6Y=", 'password' ],
				[ true, ":pbkdf2:sha1:2:20:c2FsdA==:6mwBTcctb4zNHtkqzh1B8NjeiVc=", 'password' ],
				[ true, ":pbkdf2:sha1:4096:20:c2FsdA==:SwB5AbdlSJq+rUnZJvch0GWkKcE=", 'password' ],
				[ true, ":pbkdf2:sha1:4096:16:c2EAbHQ=:Vvpqp1VICZ3MN9fwNCXgww==", "pass\x00word" ],
			]
		);
	}
}
