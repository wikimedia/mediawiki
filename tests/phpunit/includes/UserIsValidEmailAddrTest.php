<?php

class UserIsValidEmailAddrTest extends MediaWikiTestCase {

	private function checkEmail( $addr, $expected = true, $msg = '') {
		if( $msg == '' ) { $msg = "Testing $addr"; }
		$this->assertEquals(
			$expected,
			User::isValidEmailAddr( $addr ),
			$msg
		);
	}
	private function valid( $addr, $msg = '' ) {
		$this->checkEmail( $addr, true, $msg );
	}
	private function invalid( $addr, $msg = '' ) {
		$this->checkEmail( $addr, false, $msg );
	}

	function testEmailWellKnownUserAtHostDotTldAreValid() {
		$this->valid( 'user@example.com' );
		$this->valid( 'user@example.museum' );
	}
	function testEmailWithUpperCaseCharactersAreValid() {
		$this->valid( 'USER@example.com' );
		$this->valid( 'user@EXAMPLE.COM' );
		$this->valid( 'user@Example.com' );
		$this->valid( 'USER@eXAMPLE.com' );
	}
	function testEmailWithAPlusInUserName() {
		$this->valid( 'user+sub@example.com' );
		$this->valid( 'user+@example.com' );
	}
	function testEmailDoesNotNeedATopLevelDomain() {
		$this->valid( "user@localhost" );
		$this->valid( "FooBar@localdomain" );
		$this->valid( "nobody@mycompany" );
	}
	function testEmailWithWhiteSpacesBeforeOrAfterAreInvalids() {
		$this->invalid( " user@host.com" );
		$this->invalid( "user@host.com " );
		$this->invalid( "\tuser@host.com" );
		$this->invalid( "user@host.com\t" );
	}
	function testEmailWithWhiteSpacesAreInvalids() {
		$this->invalid( "User user@host" );
		$this->invalid( "first last@mycompany" );
		$this->invalid( "firstlast@my company" );
	}
	// bug 26948 : comma were matched by an incorrect regexp range
	function testEmailWithCommasAreInvalids() {
		$this->invalid( "user,foo@example.org" );
		$this->invalid( "userfoo@ex,ample.org" );
	}
	function testEmailWithHyphens() {
		$this->valid( "user-foo@example.org" );
		$this->valid( "userfoo@ex-ample.org" );
	}
	function testEmailDomainCanNotBeginWithDot() {
		$this->invalid( "user@." );
		$this->invalid( "user@.localdomain" );
		$this->invalid( "user@localdomain." );
		$this->valid( "user.@localdomain" );
		$this->valid( ".@localdomain" );
		$this->invalid( ".@a............" );
	}
	function testEmailWithFunnyCharacters() {
		$this->valid( "\$user!ex{this}@123.com" );
	}
	function testEmailTopLevelDomainCanBeNumerical() {
		$this->valid( "user@example.1234" );
	}
	function testEmailWithoutAtSignIsInvalid() {
		$this->invalid( 'useràexample.com' );
	}
	function testEmailWithOneCharacterDomainIsValid() {
		$this->valid( 'user@a' );
	}
}
