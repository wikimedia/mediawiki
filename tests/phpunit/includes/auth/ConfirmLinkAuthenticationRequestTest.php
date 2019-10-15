<?php

namespace MediaWiki\Auth;

use InvalidArgumentException;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\ConfirmLinkAuthenticationRequest
 */
class ConfirmLinkAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		return new ConfirmLinkAuthenticationRequest( $this->getLinkRequests() );
	}

	public function testConstructorException() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$linkRequests must not be empty' );
		new ConfirmLinkAuthenticationRequest( [] );
	}

	/**
	 * Get requests for testing
	 * @return AuthenticationRequest[]
	 */
	private function getLinkRequests() {
		$reqs = [];

		$mb = $this->getMockBuilder( AuthenticationRequest::class )
			->setMethods( [ 'getUniqueId' ] );
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

		return [
			'Empty request' => [
				[],
				[],
				[ 'linkRequests' => $reqs ],
			],
			'Some confirmed' => [
				[],
				[ 'confirmedLinkIDs' => [ 'Request1', 'Request3' ] ],
				[ 'confirmedLinkIDs' => [ 'Request1', 'Request3' ], 'linkRequests' => $reqs ],
			],
		];
	}

	public function testGetUniqueId() {
		$req = new ConfirmLinkAuthenticationRequest( $this->getLinkRequests() );
		$this->assertSame(
			get_class( $req ) . ':Request1|Request2|Request3',
			$req->getUniqueId()
		);
	}
}
