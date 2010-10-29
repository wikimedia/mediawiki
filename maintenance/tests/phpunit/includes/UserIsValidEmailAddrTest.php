<?php

class UserIsValidEmailAddrTest extends PHPUnit_Framework_TestCase {

	private function testEmail( $addr, $expected = true, $msg = '') {
		$this->assertEquals(
			$expected,
			User::isValidEmailAddr( $addr ),
			$msg
		);
	}
	private function valid( $addr, $msg = '' ) {
		$this->testEmail( $addr, true, $msg );
	}
	private function invalid( $addr, $msg = '' ) {
		$this->testEmail( $addr, false, $msg );
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
		$this->valid( 'user+sub@localdomain' );
	}
	function testEmailWithWhiteSpacesBeforeOrAfterAreInvalids() {
		$this->invalid( " user@host" );
		$this->invalid( "user@host " );
		$this->invalid( "\tuser@host" );
		$this->invalid( "user@host\t" );
	}
	function testEmailWithWhiteSpacesAreInvalids() {
		$this->invalid( "User user@host" );
		$this->invalid( "first last@mycompany" );
		$this->invalid( "firstlast@my company" );
	}
	function testEmailDomainCanNotBeginWithDot() {
		$this->invalid( "user@." );
		$this->invalid( "user@.localdomain" );
		$this->valid( "user@localdomain." );
		$this->valid( "user.@localdomain" );
		$this->valid( ".@localdomain" );
		$this->valid( ".@a............" );
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
	function testEmailWithOneCharacterDomainIsInvalid() {
		$this->invalid( 'user@a' );
	}
}
