<?php

/**
 * @covers Sanitizer::validateEmail
 * @todo all test methods in this class should be refactored and...
 *    use a single test method and a single data provider...
 */
class SanitizerValidateEmailTest extends PHPUnit_Framework_TestCase {

	private function checkEmail( $addr, $expected = true, $msg = '' ) {
		if ( $msg == '' ) {
			$msg = "Testing $addr";
		}

		$this->assertEquals(
			$expected,
			Sanitizer::validateEmail( $addr ),
			$msg
		);
	}

	private function valid( $addr, $msg = '' ) {
		$this->checkEmail( $addr, true, $msg );
	}

	private function invalid( $addr, $msg = '' ) {
		$this->checkEmail( $addr, false, $msg );
	}

	public function testEmailWellKnownUserAtHostDotTldAreValid() {
		$this->valid( 'user@example.com' );
		$this->valid( 'user@example.museum' );
	}

	public function testEmailWithUpperCaseCharactersAreValid() {
		$this->valid( 'USER@example.com' );
		$this->valid( 'user@EXAMPLE.COM' );
		$this->valid( 'user@Example.com' );
		$this->valid( 'USER@eXAMPLE.com' );
	}

	public function testEmailWithAPlusInUserName() {
		$this->valid( 'user+sub@example.com' );
		$this->valid( 'user+@example.com' );
	}

	public function testEmailDoesNotNeedATopLevelDomain() {
		$this->valid( "user@localhost" );
		$this->valid( "FooBar@localdomain" );
		$this->valid( "nobody@mycompany" );
	}

	public function testEmailWithWhiteSpacesBeforeOrAfterAreInvalids() {
		$this->invalid( " user@host.com" );
		$this->invalid( "user@host.com " );
		$this->invalid( "\tuser@host.com" );
		$this->invalid( "user@host.com\t" );
	}

	public function testEmailWithWhiteSpacesAreInvalids() {
		$this->invalid( "User user@host" );
		$this->invalid( "first last@mycompany" );
		$this->invalid( "firstlast@my company" );
	}

	/**
	 * T28948 : comma were matched by an incorrect regexp range
	 */
	public function testEmailWithCommasAreInvalids() {
		$this->invalid( "user,foo@example.org" );
		$this->invalid( "userfoo@ex,ample.org" );
	}

	public function testEmailWithHyphens() {
		$this->valid( "user-foo@example.org" );
		$this->valid( "userfoo@ex-ample.org" );
	}

	public function testEmailDomainCanNotBeginWithDot() {
		$this->invalid( "user@." );
		$this->invalid( "user@.localdomain" );
		$this->invalid( "user@localdomain." );
		$this->valid( "user.@localdomain" );
		$this->valid( ".@localdomain" );
		$this->invalid( ".@a............" );
	}

	public function testEmailWithFunnyCharacters() {
		$this->valid( "\$user!ex{this}@123.com" );
	}

	public function testEmailTopLevelDomainCanBeNumerical() {
		$this->valid( "user@example.1234" );
	}

	public function testEmailWithoutAtSignIsInvalid() {
		$this->invalid( 'useràexample.com' );
	}

	public function testEmailWithOneCharacterDomainIsValid() {
		$this->valid( 'user@a' );
	}
}
