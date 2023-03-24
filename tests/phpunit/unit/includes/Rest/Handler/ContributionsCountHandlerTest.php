<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler\ContributionsCountHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\ContributionsCountHandler
 */
class ContributionsCountHandlerTest extends \MediaWikiUnitTestCase {
	use DummyServicesTrait;
	use HandlerTestTrait;

	private function newHandler( $numContributions = 5 ) {
		/** @var MockObject|ContributionsLookup $mockContributionsLookup */
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributionCount' ]
		);
		$mockContributionsLookup->method( 'getContributionCount' )->willReturn( $numContributions );

		return new ContributionsCountHandler(
			$mockContributionsLookup,
			$this->getDummyUserNameUtils()
		);
	}

	public static function provideTestThatParametersAreHandledCorrectly() {
		yield [ new RequestData( [] ), 'me' ];
		yield [ new RequestData(
			[ 'queryParams' => [ 'tag' => 'test' ] ]
		), 'me' ];
		yield [ new RequestData(
			[ 'queryParams' => [ 'tag' => null ] ]
		), 'me' ];
		yield [ new RequestData(
			[ 'pathParams' => [ 'user' => 'someUser' ], 'queryParams' => [ 'tag' => '' ] ]
		), 'user' ];
		yield [ new RequestData(
			[ 'pathParams' => [ 'user' => 'someUser' ] ]
		), 'user' ];
	}

	/**
	 * @dataProvider provideTestThatParametersAreHandledCorrectly
	 */
	public function testThatParametersAreHandledCorrectly( RequestInterface $request, $mode ) {
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributionCount' ]
		);
		$username = $request->getPathParams()['user'] ?? null;
		$user = $username ? new UserIdentityValue( 42, $username ) : null;

		$tag = $request->getQueryParams()['tag'] ?? null;
		$mockContributionsLookup->method( 'getContributionCount' )
			->with( $user, $this->anything(), $tag )
			->willReturn( 123 );

		$handler = $this->newHandler( 5 );
		$validatedParams = [
			'user' => $user,
			'tag' => $tag ?? null,
		];
		$response = $this->executeHandler( $handler, $request, [ 'mode' => $mode ], [], $validatedParams, [],
			$mode === 'me' ? $this->mockRegisteredUltimateAuthority() : null );

		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testThatAnonymousUserReturns401() {
		$handler = $this->newHandler();
		$request = new RequestData( [] );
		$validatedParams = [ 'user' => null, 'tag' => null ];

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-permission-denied-anon' ), 401 )
		);
		$this->executeHandler( $handler, $request, [ 'mode' => 'me' ], [], $validatedParams, [],
			$this->mockAnonUltimateAuthority() );
	}

	public static function provideThatResponseConformsToSchema() {
		yield [ 0, [ 'count' => 0 ], [], 'me' ];
		yield [ 3, [ 'count' => 3 ], [], 'me' ];
		yield [ 0, [ 'count' => 0 ], [ 'pathParams' => [ 'user' => 'someName' ] ], 'user' ];
		yield [ 3, [ 'count' => 3 ], [ 'pathParams' => [ 'user' => 'someName' ] ] , 'user' ];
	}

	/**
	 * @dataProvider provideThatResponseConformsToSchema
	 */
	public function testThatResponseConformsToSchema( $numContributions, $expectedResponse, $config, $mode ) {
		$handler = $this->newHandler( $numContributions );
		$request = new RequestData( $config );
		$username = $request->getPathParams()['user'] ?? null;
		$validatedParams = [
			'user' => $username ? new UserIdentityValue( 42, $username ) : null,
			'tag' => null
		];

		$response = $this->executeHandlerAndGetBodyData(
			$handler, $request, [ 'mode' => $mode ], [], $validatedParams, [],
				$this->mockRegisteredUltimateAuthority()
		);

		$this->assertSame( $expectedResponse, $response );
	}

	public function testThatUnknownUserReturns404() {
		$username = 'UNKNOWN';
		$handler = $this->newHandler();
		$request = new RequestData( [ 'pathParams' => [ 'user' => $username ] ] );

		$validatedParams = [
			'user' => new UserIdentityValue( 0, $username ),
			'tag' => null
		];

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-nonexistent-user' ), 404 )
		);
		$this->executeHandler( $handler, $request, [ 'mode' => 'user' ], [], $validatedParams );
	}

	public function testThatIpUserReturns200() {
		$handler = $this->newHandler();
		$ipAddr = '127.0.0.1';
		$request = new RequestData( [ 'pathParams' => [ 'user' => $ipAddr ] ] );
		$validatedParams = [
			'user' => new UserIdentityValue( 0, $ipAddr ),
			'tag' => null
		];

		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [ 'mode' => 'user' ], [], $validatedParams );
		$this->assertArrayHasKey( 'count', $data );
	}

}
