<?php

use MediaWiki\Parser\Sanitizer;

/**
 * @covers Sanitizer::validateEmail
 * @todo should be made a pure unit test once ::validateEmail is migrated to proper DI
 */
class SanitizerValidateEmailTest extends MediaWikiIntegrationTestCase {

	public static function provideValidEmails() {
		yield 'normal #1' => [ 'user@example.com' ];
		yield 'normal #2' => [ 'user@example.museum' ];
		yield 'with uppercase #1' => [ 'USER@example.com' ];
		yield 'with uppercase #2' => [ 'user@EXAMPLE.COM' ];
		yield 'with uppercase #3' => [ 'user@Example.com' ];
		yield 'with uppercase #4' => [ 'USER@eXAMPLE.com' ];
		yield 'with plus #1' => [ 'user+sub@example.com' ];
		yield 'with plus #2' => [ 'user+@example.com' ];
		yield 'TLD not neeeded #1' => [ "user@localhost" ];
		yield 'TLD not neeeded #2' => [ "FooBar@localdomain" ];
		yield 'TLD not neeeded #3' => [ "nobody@mycompany" ];

		yield 'with hythen #1' => [ "user-foo@example.org" ];
		yield 'with hythen #2' => [ "userfoo@ex-ample.org" ];

		yield 'email with dot #1' => [ "user.@localdomain" ];
		yield 'email with dot #2' => [ ".@localdomain" ];

		yield 'funny characters' => [ "\$user!ex{this}@123.com" ];
		yield 'numerical TLD' => [ "user@example.1234" ];
		yield 'only one character needed' => [ 'user@a' ];
	}

	/**
	 * @dataProvider provideValidEmails
	 */
	public function testValidateEmail_valid( string $addr ) {
		$this->assertTrue( Sanitizer::validateEmail( $addr ) );
	}

	public static function provideInvalidEmails() {
		yield 'whitespace before #1' => [ " user@host.com" ];
		yield 'whitespace before #2' => [ "\tuser@host.com" ];
		yield 'whitespace after #1' => [ "user@host.com " ];
		yield 'whitespace after #2' => [ "user@host.com\t" ];
		yield 'with whitespace #1' => [ "User user@host" ];
		yield 'with whitespace #2' => [ "first last@mycompany" ];
		yield 'with whitespace #3' => [ "firstlast@my company" ];

		// T28948 : comma were matched by an incorrect regexp range
		yield 'invalid comma #1' => [ "user,foo@example.org" ];
		yield 'invalid comma #2' => [ "userfoo@ex,ample.org" ];

		yield 'domain beginning with dot #1' => [ "user@." ];
		yield 'domain beginning with dot #2' => [ "user@.localdomain" ];
		yield 'domain beginning with dot #3' => [ "user@localdomain." ];
		yield 'domain beginning with dot #4' => [ ".@a............" ];

		yield 'missing @' => [ 'useràexample.com' ];
	}

	/**
	 * @dataProvider provideInvalidEmails
	 */
	public function testValidateEmail_invalid( string $addr ) {
		$this->assertFalse( Sanitizer::validateEmail( $addr ) );
	}
}
