<?php

namespace MediaWiki\Auth;

use InvalidArgumentException;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\ConfirmLinkAuthenticationRequest
 */
class ConfirmLinkAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		return new ConfirmLinkAuthenticationRequest( self::getLinkRequests() );
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
	private static function getLinkRequests() {
		$reqs = [];

		for ( $i = 1; $i <= 3; $i++ ) {
			$req = new class( "Request$i" ) extends AuthenticationRequest {
				private $uniqueId;

				public function __construct( $uniqueId ) {
					$this->uniqueId = $uniqueId;
				}

				public function getFieldInfo() {
					return [];
				}

				public function getUniqueId() {
					return $this->uniqueId;
				}
			};

			$reqs[$req->getUniqueId()] = $req;
		}

		return $reqs;
	}

	public static function provideLoadFromSubmission() {
		$reqs = self::getLinkRequests();

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
		$req = new ConfirmLinkAuthenticationRequest( self::getLinkRequests() );
		$this->assertSame(
			get_class( $req ) . ':Request1|Request2|Request3',
			$req->getUniqueId()
		);
	}
}
