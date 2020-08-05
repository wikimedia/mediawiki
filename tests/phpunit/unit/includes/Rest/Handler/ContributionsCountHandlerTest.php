<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler\ContributionsCountHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\User\UserNameUtils;
use PHPUnit\Framework\MockObject\MockObject;
use RequestContext;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\ContributionsCountHandler
 */
class ContributionsCountHandlerTest extends \MediaWikiUnitTestCase {
	use ContributionsTestTrait;
	use HandlerTestTrait;

	private function newHandler( $numContributions = 5 ) {
		/** @var MockObject|ContributionsLookup $mockContributionsLookup */
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributionCount' ]
		);

		$mockContributionsLookup->method( 'getContributionCount' )->willReturn( $numContributions );
		$mockUserNameUtils = $this->createNoOpMock( UserNameUtils::class,
			[ 'isIP' ]
		);

		$mockUserNameUtils->method( 'isIP' )
			->willReturnCallback( function ( $name ) {
				return $name === '127.0.0.1';
			} );

		return new ContributionsCountHandler(
			$mockContributionsLookup,
			$mockUserNameUtils
		);
	}

	public function provideTestThatParametersAreHandledCorrectly() {
		yield [ new RequestData( [] ), 'me' ];
		yield [ new RequestData(
			[ 'queryParams' => [ 'tag' => 'test' ] ]
		), 'me' ];
		yield [ new RequestData(
			[ 'queryParams' => [ 'tag' => null ] ]
		), 'me' ];
		yield [ new RequestData(
			[ 'pathParams' => [ 'name' => 'someUser' ], 'queryParams' => [ 'tag' => '' ] ]
		), 'user' ];
		yield [ new RequestData(
			[ 'pathParams' => [ 'name' => 'someUser' ] ]
		), 'user' ];
	}

	/**
	 * @param RequestInterface $request
	 * @dataProvider provideTestThatParametersAreHandledCorrectly
	 */
	public function testThatParametersAreHandledCorrectly( RequestInterface $request, $mode ) {
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributionCount' ]
		);
		$user = $this->makeMockUser( false );
		RequestContext::getMain()->setUser( $user );

		$tag = $request->getQueryParams()['tag'] ?? null;
		$mockContributionsLookup->method( 'getContributionCount' )
			->with( $user, $user, $tag )
			->willReturn( 123 );

		$handler = $this->newHandler( 5 );
		$username = $request->getPathParams()['name'] ?? null;
		$validatedParams = [
			'name' => $username ? $this->makeMockUser( $username ) : null,
			'tag' => $tag ?? null,
		];
		$response = $this->executeHandler( $handler, $request, [ 'mode' => $mode ], [], $validatedParams );

		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testThatAnonymousUserReturns401() {
		$handler = $this->newHandler();
		$request = new RequestData( [] );
		$validatedParams = [ 'name' => null, 'tag' => null ];
		$user = $this->makeMockUser( '127.0.0.1' );
		RequestContext::getMain()->setUser( $user );

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-permission-denied-anon' ), 401 )
		);
		$this->executeHandler( $handler, $request, [ 'mode' => 'me' ], [], $validatedParams );
	}

	public function provideThatResponseConformsToSchema() {
		yield [ 0, [ 'count' => 0 ], [], 'me' ];
		yield [ 3, [ 'count' => 3 ], [], 'me' ];
		yield [ 0, [ 'count' => 0 ], [ 'pathParams' => [ 'name' => 'someName' ] ], 'user' ];
		yield [ 3, [ 'count' => 3 ], [ 'pathParams' => [ 'name' => 'someName' ] ] , 'user' ];
	}

	/**
	 * @dataProvider provideThatResponseConformsToSchema
	 */
	public function testThatResponseConformsToSchema( $numContributions, $expectedResponse, $config, $mode ) {
		$handler = $this->newHandler( $numContributions );
		$request = new RequestData( $config );
		$username = $request->getPathParams()['name'] ?? null;
		$validatedParams = [
			'name' => $this->makeMockUser( $username ),
			'tag' => null
		];
		$user = $this->makeMockUser( 'Betty' );
		RequestContext::getMain()->setUser( $user );

		$response = $this->executeHandlerAndGetBodyData(
			$handler, $request, [ 'mode' => $mode ], [], $validatedParams
		);

		$this->assertSame( $expectedResponse, $response );
	}

	public function testThatUnknownUserReturns404() {
		$username = 'UNKNOWN';
		$handler = $this->newHandler();
		$request = new RequestData( [ 'pathParams' => [ 'name' => $username ] ] );

		$user = $this->makeMockUser( 'Betty' );
		RequestContext::getMain()->setUser( $user );

		$validatedParams = [
			'name' => $this->makeMockUser( $username ),
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
		$request = new RequestData( [ 'pathParams' => [ 'name' => $ipAddr ] ] );
		$validatedParams = [
			'name' => $this->makeMockUser( $ipAddr ),
			'tag' => null
		];
		$user = $this->makeMockUser( 'Betty' );
		RequestContext::getMain()->setUser( $user );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [ 'mode' => 'user' ], [], $validatedParams );
		$this->assertArrayHasKey( 'count', $data );
	}

}
