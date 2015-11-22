<?php

namespace MediaWiki\Auth;

use InvalidArgumentException;

require_once 'AuthenticationRequestTestCase.php';

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\ConfirmLinkAuthenticationRequest
 */
class ConfirmLinkAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = array() ) {
		return new ConfirmLinkAuthenticationRequest( $this->getLinkRequests() );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage $linkRequests must not be empty
	 */
	public function testConstructorException() {
		new ConfirmLinkAuthenticationRequest( array() );
	}

	/**
	 * Get requests for testing
	 * @return AuthenticationRequest[]
	 */
	private function getLinkRequests() {
		$reqs = array();

		$mb = $this->getMockBuilder( 'MediaWiki\\Auth\\AuthenticationRequest' )
			->setMethods( array( 'getUniqueId' ) );
		for ( $i = 1; $i <= 3; $i++ ) {
			$req = $mb->getMockForAbstractClass();
			$req->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( "Request$i" ) );
			$reqs[$req->getUniqueId()] = $req;
		}

		return $reqs;
	}

	public function provideLoadFromSubmission() {
		$reqs = $this->getLinkRequests();

		return array(
			'Empty request' => array(
				array(),
				array(),
				array( 'linkRequests' => $reqs ),
			),
			'Some confirmed' => array(
				array(),
				array( 'confirmedLinkIDs' => array( 'Request1', 'Request3' ) ),
				array( 'confirmedLinkIDs' => array( 'Request1', 'Request3' ), 'linkRequests' => $reqs ),
			),
		);
	}

	public function testGetUniqueId() {
		$req = new ConfirmLinkAuthenticationRequest( $this->getLinkRequests() );
		$this->assertSame(
			get_class( $req ) . ':Request1|Request2|Request3',
			$req->getUniqueId()
		);
	}
}
