<?php

class MailAddressTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers MailAddress::__construct
	 */
	public function testConstructor() {
		$ma = new MailAddress( 'foo@bar.baz', 'UserName', 'Real name' );
		$this->assertInstanceOf( MailAddress::class, $ma );
	}

	/**
	 * @covers MailAddress::newFromUser
	 */
	public function testNewFromUser() {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test only works on non-Windows platforms' );
		}
		$user = $this->createMock( User::class );
		$user->expects( $this->any() )->method( 'getName' )->will(
			$this->returnValue( 'UserName' )
		);
		$user->expects( $this->any() )->method( 'getEmail' )->will(
			$this->returnValue( 'foo@bar.baz' )
		);
		$user->expects( $this->any() )->method( 'getRealName' )->will(
			$this->returnValue( 'Real name' )
		);

		$ma = MailAddress::newFromUser( $user );
		$this->assertInstanceOf( MailAddress::class, $ma );
		$this->setMwGlobals( 'wgEnotifUseRealName', true );
		$this->assertEquals( '"Real name" <foo@bar.baz>', $ma->toString() );
		$this->setMwGlobals( 'wgEnotifUseRealName', false );
		$this->assertEquals( '"UserName" <foo@bar.baz>', $ma->toString() );
	}

	/**
	 * @covers MailAddress::toString
	 * @dataProvider provideToString
	 */
	public function testToString( $useRealName, $address, $name, $realName, $expected ) {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test only works on non-Windows platforms' );
		}
		$this->setMwGlobals( 'wgEnotifUseRealName', $useRealName );
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

	/**
	 * @covers MailAddress::__toString
	 */
	public function test__ToString() {
		$ma = new MailAddress( 'some@email.com', 'UserName', 'A real name' );
		$this->assertEquals( $ma->toString(), (string)$ma );
	}
}
