<?php

/**
 * @group large
 * @covers AbstractPbkdf2Password
 * @covers Pbkdf2PasswordUsingOpenSSL
 * @requires function openssl_pbkdf2
 */
class Pbkdf2PasswordUsingOpenSSLTest extends Pbkdf2PasswordTestCase {
	protected static function getPbkdf2PasswordClass() {
		return Pbkdf2PasswordUsingOpenSSL::class;
	}
}
