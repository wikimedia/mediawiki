<?php

class MailAddressTest extends MediaWikiTestCase {

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
		return array(
			array( true, 'foo@bar.baz', 'FooBar', 'Foo Bar', 'Foo Bar <foo@bar.baz>' ),
			array( true, 'foo@bar.baz', 'UserName', null, 'UserName <foo@bar.baz>' ),
			array( true, 'foo@bar.baz', 'AUser', 'My real name', 'My real name <foo@bar.baz>' ),
			array( true, 'foo@bar.baz', 'A.user.name', 'my@real.name', '"my@real.name" <foo@bar.baz>' ),
			array( false, 'foo@bar.baz', 'AUserName', 'Some real name', 'AUserName <foo@bar.baz>' ),
			array( false, 'foo@bar.baz', '', '', 'foo@bar.baz' ),
			array( true, 'foo@bar.baz', '', '', 'foo@bar.baz' ),
		);
	}

	/**
	 * @covers MailAddress::__toString
	 */
	public function test__ToString() {
		$ma = new MailAddress( 'some@email.com', 'UserName', 'A real name' );
		$this->assertEquals( $ma->toString(), (string)$ma );
	}

}