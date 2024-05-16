<?php

/**
 * @group large
 * @covers \MediaWiki\Password\AbstractPbkdf2Password
 * @covers \MediaWiki\Password\Pbkdf2PasswordUsingOpenSSL
 * @requires function openssl_pbkdf2
 */
class Pbkdf2PasswordUsingOpenSSLTest extends Pbkdf2PasswordTestCase {
	protected static function getPbkdf2PasswordClass() {
		return Pbkdf2PasswordUsingOpenSSL::class;
	}
}
