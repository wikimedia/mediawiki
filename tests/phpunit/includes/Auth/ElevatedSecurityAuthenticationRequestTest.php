<?php

namespace MediaWiki\Auth;

use BadMethodCallException;
use LogicException;
use MediaWiki\Context\RequestContext;
use MWTimestamp;
use StatusValue;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\AuthenticationRequest
 */
class ElevatedSecurityAuthenticationRequestTest extends AuthenticationRequestTestCase {

	public function tearDown(): void {
		MWTimestamp::setFakeTime( false );
		parent::tearDown();
	}

	protected function getInstance( array $args = [] ) {
		[ $session, $securityLevel ] = $args;
		return ElevatedSecurityAuthenticationRequest::create( $session, $securityLevel );
	}

	public function testCreate() {
		$session = RequestContext::getMain()->getRequest()->getSession();
		$user = $this->getTestUser()->getUser();
		try {
			$req = ElevatedSecurityAuthenticationRequest::create( $session, 'foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( LogicException $e ) {
		}

		$session->setUser( $user );
		$req = ElevatedSecurityAuthenticationRequest::create( $session, 'foo' );
		$this->assertInstanceOf( ElevatedSecurityAuthenticationRequest::class, $req );
		$this->assertSame( $user->getId(), $req->userId );
		$this->assertSame( 'foo', $req->securityLevel );
	}

	/**
	 * @dataProvider provideGetFieldInfoCallbacks
	 * @covers \MediaWiki\Auth\ElevatedSecurityAuthenticationRequest::getFieldInfo
	 * @param array $args
	 */
	public function testGetFieldInfo( array $args ) {
		// HACK getTestUser() cannot be called in a data provider so we must generate the session here.
		[ $session, $securityLevel ] = $args[0]();
		parent::testGetFieldInfo( [ $session, $securityLevel ] );
	}

	public function provideGetFieldInfoCallbacks() {
		yield [ [ function () {
			$session = RequestContext::getMain()->getRequest()->getSession();
			$user = $this->getTestUser()->getUser();
			$session->setUser( $user );
			return [ $session, 'foo' ];
		} ] ];
	}

	/**
	 * @dataProvider provideLoadFromSubmissionCallbacks
	 * @covers \MediaWiki\Auth\ElevatedSecurityAuthenticationRequest::loadFromSubmission
	 * @param array $args An array containing a single callable which should return the real
	 *   $args, $data and $expectState in an array.
	 * @param array|null $data Not used
	 * @param null $expectState Not used
	 */
	public function testLoadFromSubmission( array $args, ?array $data = null, $expectState = null ) {
		// HACK getTestUser() cannot be called in a data provider so we must generate the
		// arguments here. To keep compatible with the parent method, pass a callback as $args[0]
		[ $args, $data, $expectState ] = array_values( call_user_func( $args[0] ) );
		parent::testLoadFromSubmission( $args, $data, $expectState );
	}

	public static function provideLoadFromSubmission() {
		// Not used in this class, we override testLoadFromSubmission above to use
		// provideLoadFromSubmissionCallbacks instead
		throw new BadMethodCallException( 'Should not be called' );
	}

	public function provideLoadFromSubmissionCallbacks() {
		yield 'not present' => [ [ function () {
			$session = RequestContext::getMain()->getRequest()->getSession();
			$user = $this->getTestUser()->getUser();
			$session->setUser( $user );
			// HACK calculate the same token create() will. Stop the clock to make sure it is the same.
			MWTimestamp::setFakeTime( time() );
			$token = $session->getToken( [ $user->getId(), 'foo' ], 'reauth' );
			return [
				'getInstance args' => [ $session, 'foo' ],
				'request data' => [],
				'expected state' => [
					'userId' => $user->getId(),
					'securityLevel' => 'foo',
					'token' => $token,
					'validationStatus' => StatusValue::newFatal( 'authmanager-elevatedsecurity-missing-token' ),
					'session' => $session,
				],
			];
		} ] ];
		yield 'normal' => [ [ function () {
			$session = RequestContext::getMain()->getRequest()->getSession();
			$user = $this->getTestUser()->getUser();
			$session->setUser( $user );
			$token = $session->getToken( [ $user->getId(), 'foo' ], 'reauth' )->toString();
			return [
				'getInstance args' => [ $session, 'foo' ],
				'request data' => [
					'elevatedSecurityToken' => $token,
				],
				'expected state' => [
					'userId' => $user->getId(),
					'securityLevel' => 'foo',
					'token' => $token,
					'validationStatus' => StatusValue::newGood(),
					'session' => $session,
				],
			];
		} ] ];
		yield 'fake level in object' => [ [ function () {
			$session = RequestContext::getMain()->getRequest()->getSession();
			$user = $this->getTestUser()->getUser();
			$session->setUser( $user );
			$correctToken = $session->getToken( [ $user->getId(), 'foo' ], 'reauth' )->toString();
			$objectToken = $session->getToken( [ $user->getId(), 'bar' ], 'reauth' )->toString();
			return [
				'getInstance args' => [ $session, 'bar' ],
				'request data' => [
					'elevatedSecurityToken' => $correctToken,
				],
				'expected state' => [
					'userId' => $user->getId(),
					'securityLevel' => 'bar',
					'token' => $objectToken,
					'validationStatus' => StatusValue::newFatal( 'authmanager-elevatedsecurity-invalid-token' ),
					'session' => $session,
				],
			];
		} ] ];
		yield 'fake level in token' => [ [ function () {
			$session = RequestContext::getMain()->getRequest()->getSession();
			$user = $this->getTestUser()->getUser();
			$session->setUser( $user );
			$correctToken = $session->getToken( [ $user->getId(), 'foo' ], 'reauth' )->toString();
			$submittedToken = $session->getToken( [ $user->getId(), 'bar' ], 'reauth' )->toString();
			return [
				'getInstance args' => [ $session, 'foo' ],
				'request data' => [
					'elevatedSecurityToken' => $submittedToken,
				],
				'expected state' => [
					'userId' => $user->getId(),
					'securityLevel' => 'foo',
					'token' => $correctToken,
					'validationStatus' => StatusValue::newFatal( 'authmanager-elevatedsecurity-invalid-token' ),
					'session' => $session,
				],
			];
		} ] ];
		yield 'wrong user' => [ [ function () {
			$session = RequestContext::getMain()->getRequest()->getSession();
			$user1 = $this->getTestUser()->getUser();
			$user2 = $this->getTestUser( [ 'sysop' ] )->getUser();
			$session->setUser( $user1 );
			$correctToken = $session->getToken( [ $user1->getId(), 'foo' ], 'reauth' )->toString();
			$submittedToken = $session->getToken( [ $user2->getId(), 'foo' ], 'reauth' )->toString();
			return [
				'getInstance args' => [ $session, 'foo' ],
				'request data' => [
					'elevatedSecurityToken' => $submittedToken,
				],
				'expected state' => [
					'userId' => $user1->getId(),
					'securityLevel' => 'foo',
					'token' => $correctToken,
					'validationStatus' => StatusValue::newFatal( 'authmanager-elevatedsecurity-invalid-token' ),
					'session' => $session,
				],
			];
		} ] ];
	}

	public function testLoadFromSubmission_error() {
		$session = RequestContext::getMain()->getRequest()->getSession();
		$user = $this->getTestUser()->getUser();
		$session->setUser( $user );
		$req = ElevatedSecurityAuthenticationRequest::create( $session, 'foo' );

		$req = unserialize( serialize( $req ) );
		try {
			$req->loadFromSubmission( [ 'elevatedSecurityToken' => 'fake' ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( LogicException $e ) {
		}
		$this->assertTrue( true );
	}

	public function testValidate() {
		$session = RequestContext::getMain()->getRequest()->getSession();
		$user = $this->getTestUser()->getUser();
		$session->setUser( $user );

		$req = ElevatedSecurityAuthenticationRequest::create( $session, 'foo' );
		$status = $req->validate();
		$this->assertEquals(
			StatusValue::newFatal( 'authmanager-elevatedsecurity-not-validated' ), $status );

		$req = ElevatedSecurityAuthenticationRequest::create( $session, 'foo' );
		$req->loadFromSubmission( [ 'elevatedSecurityToken' => 'bad' ] );
		$status = $req->validate();
		$this->assertEquals(
			StatusValue::newFatal( 'authmanager-elevatedsecurity-invalid-token' ), $status );

		$req = ElevatedSecurityAuthenticationRequest::create( $session, 'foo' );
		$token = $req->getFieldInfo()['elevatedSecurityToken']['value'];
		$req->loadFromSubmission( [ 'elevatedSecurityToken' => $token ] );
		$status = $req->validate();
		$this->assertEquals( StatusValue::newGood(), $status );
	}

}
