<?php

namespace MediaWiki\Auth;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\ButtonAuthenticationRequest
 */
class ButtonAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		$data = array_intersect_key( $args, array( 'name' => 1, 'label' => 1 ) );
		return ButtonAuthenticationRequest::__set_state( $data );
	}

	public static function provideGetFieldInfo() {
		return array(
			array( array( 'name' => 'foo', 'label' => 'bar' ) )
		);
	}

	public function provideLoadFromSubmission() {
		return array(
			array(
				'Empty request',
				array( 'name' => 'foo', 'label' => 'bar' ),
				array(),
				false
			),
			array(
				'Button present',
				array( 'name' => 'foo', 'label' => 'bar' ),
				array( 'foo' => 'Foobar' ),
				array( 'name' => 'foo', 'label' => 'bar', 'foo' => true )
			),
		);
	}

	public function testGetUniqueId() {
		$req = new ButtonAuthenticationRequest( 'foo', wfMessage( 'bar' ) );
		$this->assertSame(
			'MediaWiki\\Auth\\ButtonAuthenticationRequest:foo', $req->getUniqueId()
		);
	}

	public function testGetRequestByName() {
		$reqs = array();
		$reqs['testOne'] = new ButtonAuthenticationRequest( 'foo', wfMessage( 'msg' ) );
		$reqs[] = new ButtonAuthenticationRequest( 'bar', wfMessage( 'msg1' ) );
		$reqs[] = new ButtonAuthenticationRequest( 'bar', wfMessage( 'msg2' ) );
		$reqs['testSub'] = $this->getMockBuilder( 'MediaWiki\\Auth\\ButtonAuthenticationRequest' )
			->setConstructorArgs( array( 'subclass', wfMessage( 'msg3' ) ) )
			->getMock();

		$this->assertNull( ButtonAuthenticationRequest::getRequestByName( $reqs, 'missing' ) );
		$this->assertSame(
			$reqs['testOne'], ButtonAuthenticationRequest::getRequestByName( $reqs, 'foo' )
		);
		$this->assertNull( ButtonAuthenticationRequest::getRequestByName( $reqs, 'bar' ) );
		$this->assertSame(
			$reqs['testSub'], ButtonAuthenticationRequest::getRequestByName( $reqs, 'subclass' )
		);
	}
}
