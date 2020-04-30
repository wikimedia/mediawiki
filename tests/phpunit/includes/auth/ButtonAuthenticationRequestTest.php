<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\ButtonAuthenticationRequest
 */
class ButtonAuthenticationRequestTest extends AuthenticationRequestTestCase {

	protected function getInstance( array $args = [] ) {
		$data = array_intersect_key( $args, [ 'name' => 1, 'label' => 1, 'help' => 1 ] );
		return ButtonAuthenticationRequest::__set_state( $data );
	}

	public static function provideGetFieldInfo() {
		return [
			[ [ 'name' => 'foo', 'label' => 'bar', 'help' => 'baz' ] ]
		];
	}

	public function provideLoadFromSubmission() {
		return [
			'Empty request' => [
				[ 'name' => 'foo', 'label' => 'bar', 'help' => 'baz' ],
				[],
				false
			],
			'Button present' => [
				[ 'name' => 'foo', 'label' => 'bar', 'help' => 'baz' ],
				[ 'foo' => 'Foobar' ],
				[ 'name' => 'foo', 'label' => 'bar', 'help' => 'baz', 'foo' => true ]
			],
		];
	}

	public function testGetUniqueId() {
		$req = new ButtonAuthenticationRequest( 'foo', wfMessage( 'bar' ), wfMessage( 'baz' ) );
		$this->assertSame(
			'MediaWiki\\Auth\\ButtonAuthenticationRequest:foo', $req->getUniqueId()
		);
	}

	public function testGetRequestByName() {
		$reqs = [];
		$reqs['testOne'] = new ButtonAuthenticationRequest(
			'foo', wfMessage( 'msg' ), wfMessage( 'help' )
		);
		$reqs[] = new ButtonAuthenticationRequest( 'bar', wfMessage( 'msg1' ), wfMessage( 'help1' ) );
		$reqs[] = new ButtonAuthenticationRequest( 'bar', wfMessage( 'msg2' ), wfMessage( 'help2' ) );
		$reqs['testSub'] =
			new ButtonAuthenticationRequest( 'subclass', wfMessage( 'msg3' ), wfMessage( 'help3' ) );

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
