<?php

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers PasswordDomainAuthenticationRequest
 * @uses PasswordAuthenticationRequest
 */
class PasswordDomainAuthenticationRequestTest extends AuthenticationRequestTestCase {
	protected static $class = 'PasswordDomainAuthenticationRequestTest_MockAuthenticationRequest';

	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage PasswordDomainAuthenticationRequest must override domainList()
	 */
	public function testBaseClass() {
		PasswordDomainAuthenticationRequest::getFieldInfo();
	}

	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage PasswordDomainAuthenticationRequestMock must override domainList()
	 */
	public function testSubclass() {
		$class = get_class( $this->getMockForAbstractClass(
			'PasswordDomainAuthenticationRequest', array(), 'PasswordDomainAuthenticationRequestMock'
		) );
		$class::getFieldInfo();
	}

	public function provideNewFromSubmission() {
		return array(
			array(
				'Empty request',
				array(),
				null
			),
			array(
				'Username + password',
				array( 'username' => 'User', 'password' => 'Bar' ),
				null
			),
			array(
				'Username + password + domain',
				$data = array( 'username' => 'User', 'password' => 'Bar', 'domain' => 'domain1' ),
				$data
			),
			array(
				'Username empty',
				array( 'username' => '', 'password' => 'Bar', 'domain' => 'domain1' ),
				null
			),
			array(
				'Password empty',
				array( 'username' => 'User', 'password' => '', 'domain' => 'domain1' ),
				null
			),
			array(
				'Domain empty',
				array( 'username' => 'User', 'password' => 'Bar', 'domain' => '' ),
				null
			),
			array(
				'Domain invalid',
				array( 'username' => 'User', 'password' => 'Bar', 'domain' => 'invalid' ),
				null
			),
		);
	}
}

class PasswordDomainAuthenticationRequestTest_MockAuthenticationRequest
	extends PasswordDomainAuthenticationRequest
{
		protected static function domainList() {
			return array( 'domain1', 'domain2' );
		}
}
