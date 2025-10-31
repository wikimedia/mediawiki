<?php

namespace MediaWiki\Tests\Mail;

use Generator;
use MediaWiki\Mail\MailAddress;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;

/**
 * @group Mail
 * @covers \MediaWiki\Mail\MailAddress
 */
class MailAddressTest extends MediaWikiIntegrationTestCase {

	public function testNewFromUser() {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test only works on non-Windows platforms' );
		}
		$user = $this->createMock( User::class );
		$user->method( 'getUser' )->willReturn( new UserIdentityValue( 42, 'UserName' ) );
		$user->method( 'getEmail' )->willReturn( 'foo@bar.baz' );
		$user->method( 'getRealName' )->willReturn( 'Real name' );

		$ma = MailAddress::newFromUser( $user );
		$this->assertInstanceOf( MailAddress::class, $ma );

		// No setMwGlobals() in a unit test, need some manual logic
		// Don't worry about messing with the actual value, MediaWikiUnitTestCase restores it
		global $wgEnotifUseRealName;

		$wgEnotifUseRealName = true;
		$this->assertEquals( '"Real name" <foo@bar.baz>', $ma->toString() );

		$wgEnotifUseRealName = false;
		$this->assertEquals( '"UserName" <foo@bar.baz>', $ma->toString() );
	}

	/**
	 * @dataProvider provideEquals
	 */
	public function testEquals( MailAddress $first, MailAddress $second, bool $expected ) {
		$this->assertSame( $expected, $first->equals( $second ) );
	}

	public static function provideEquals(): Generator {
		$base = new MailAddress( 'a@b.c', 'name', 'realname' );

		yield 'Different addresses' => [ $base, new MailAddress( 'xxx', 'name', 'realname' ), false ];
		yield 'Different names' => [ $base, new MailAddress( 'a@b.c', 'other name', 'realname' ), false ];
		yield 'Different real names' => [ $base, new MailAddress( 'a@b.c', 'name', 'other realname' ), false ];
		yield 'Equal' => [ $base, new MailAddress( 'a@b.c', 'name', 'realname' ), true ];
	}

	/**
	 * @dataProvider provideToString
	 */
	public function testToString( $useRealName, $address, $name, $realName, $expected ) {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test only works on non-Windows platforms' );
		}
		// No setMwGlobals() in a unit test, need some manual logic
		// Don't worry about messing with the actual value, MediaWikiUnitTestCase restores it
		global $wgEnotifUseRealName;
		$wgEnotifUseRealName = $useRealName;

		$ma = new MailAddress( $address, $name, $realName );
		$this->assertEquals( $expected, $ma->toString() );
	}

	public static function provideToString() {
		return [
			[ true, 'foo@bar.baz', 'FooBar', 'Foo Bar', '"Foo Bar" <foo@bar.baz>' ],
			[ true, 'foo@bar.baz', 'UserName', null, '"UserName" <foo@bar.baz>' ],
			[ true, 'foo@bar.baz', 'AUser', 'My real name', '"My real name" <foo@bar.baz>' ],
			[ true, 'foo@bar.baz', 'AUser', 'My "real" name', '"My \"real\" name" <foo@bar.baz>' ],
			[ true, 'foo@bar.baz', 'AUser', 'My "A/B" test', '"My \"A/B\" test" <foo@bar.baz>' ],
			[ true, 'foo@bar.baz', 'AUser', 'E=MC2', '=?UTF-8?Q?E=3DMC2?= <foo@bar.baz>' ],
			// A backslash (\) should be escaped (\\). In a string literal that is \\\\ (4x).
			[ true, 'foo@bar.baz', 'AUser', 'My "B\C" test', '"My \"B\\\\C\" test" <foo@bar.baz>' ],
			[ true, 'foo@bar.baz', 'A.user.name', 'my@real.name', '"my@real.name" <foo@bar.baz>' ],
			[ false, 'foo@bar.baz', 'AUserName', 'Some real name', '"AUserName" <foo@bar.baz>' ],
			[ false, 'foo@bar.baz', '', '', 'foo@bar.baz' ],
			[ true, 'foo@bar.baz', '', '', 'foo@bar.baz' ],
			[ true, '', '', '', '' ],
		];
	}

	public function test__ToString() {
		$ma = new MailAddress( 'some@email.com', 'UserName', 'A real name' );
		$this->assertEquals( $ma->toString(), (string)$ma );
	}
}
