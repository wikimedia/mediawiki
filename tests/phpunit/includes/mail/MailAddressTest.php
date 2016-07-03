<?php

class MailAddressTest extends MediaWikiTestCase {

	/**
	 * @covers MailAddress::__construct
	 */
	public function testConstructor() {
		$ma = new MailAddress( 'foo@bar.baz', 'UserName', 'Real name' );
		$this->assertInstanceOf( 'MailAddress', $ma );
	}

	/**
	 * @covers MailAddress::newFromUser
	 */
	public function testNewFromUser() {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test only works on non-Windows platforms' );
		}
		$user = $this->getMock( 'User' );
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
		$this->assertInstanceOf( 'MailAddress', $ma );
		$this->setMwGlobals( 'wgEnotifUseRealName', true );
		$this->assertEquals( 'Real name <foo@bar.baz>', $ma->toString() );
		$this->setMwGlobals( 'wgEnotifUseRealName', false );
		$this->assertEquals( 'UserName <foo@bar.baz>', $ma->toString() );
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
			[ true, 'foo@bar.baz', 'FooBar', 'Foo Bar', 'Foo Bar <foo@bar.baz>' ],
			[ true, 'foo@bar.baz', 'UserName', null, 'UserName <foo@bar.baz>' ],
			[ true, 'foo@bar.baz', 'AUser', 'My real name', 'My real name <foo@bar.baz>' ],
			[ true, 'foo@bar.baz', 'A.user.name', 'my@real.name', '"my@real.name" <foo@bar.baz>' ],
			[ false, 'foo@bar.baz', 'AUserName', 'Some real name', 'AUserName <foo@bar.baz>' ],
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
