<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler\ContributionsCountHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Revision\ContributionsLookup;
use PHPUnit\Framework\MockObject\MockObject;
use RequestContext;
use User;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\ContributionsCountHandler
 */
class ContributionsCountHandlerTest extends \MediaWikiUnitTestCase {

	use HandlerTestTrait;

	private function newHandler( $numRevisions = 5 ) {
		/** @var MockObject|ContributionsLookup $mockContributionsLookup */
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributionCount' ]
		);
		$mockContributionsLookup->method( 'getContributionCount' )->willReturn( $numRevisions );
		$handler = new ContributionsCountHandler( $mockContributionsLookup );
		return $handler;
	}

	public function provideTestThatParametersAreHandledCorrectly() {
		yield [ new RequestData( [] ) ];
		yield [ new RequestData(
			[ 'queryParams' => [ 'tag' => 'test' ] ]
		) ];
		yield [ new RequestData(
			[ 'queryParams' => [ 'tag' => null ] ]
		) ];
		yield [ new RequestData(
			[ 'queryParams' => [ 'tag' => '' ] ]
		) ];
	}

	/**
	 * @param RequestInterface $request
	 * @dataProvider provideTestThatParametersAreHandledCorrectly
	 */
	public function testThatParametersAreHandledCorrectly( RequestInterface $request ) {
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributionCount' ]
		);
		$user = $this->makeMockUser( false );
		RequestContext::getMain()->setUser( $user );

		$tag = $request->getQueryParams()['tag'] ?? null;
		$mockContributionsLookup->method( 'getContributionCount' )
			->with( $user, $user, $tag )
			->willReturn( 123 );

		$handler = new ContributionsCountHandler( $mockContributionsLookup );

		$response = $this->executeHandler( $handler, $request );

		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testThatAnonymousUserReturns401() {
		$handler = $this->newHandler();
		$request = new RequestData( [] );
		RequestContext::getMain()->setUser( new User() );

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-permission-denied-anon' ), 401 )
		);
		$response = $this->executeHandler( $handler, $request );
		$this->assertSame( 401, $response->getStatusCode() );
	}

	private function makeMockUser( $anon = false ) {
		$user = $this->createNoOpMock( User::class, [ 'isAnon' ] );
		$user->method( 'isAnon' )->willReturn( $anon );
		return $user;
	}

	public function provideThatResponseConformsToSchema() {
		$basePath = 'https://wiki.example.com/rest/coredev/v0/me/contributions/count';
		yield [ 0, [ 'count' => 0 ] ];
		yield [ 3, [ 'count' => 3 ] ];
	}

	/**
	 * @dataProvider provideThatResponseConformsToSchema
	 */
	public function testThatResponseConformsToSchema( $numRevisions, $expectedResponse ) {
		$handler = $this->newHandler( $numRevisions );
		$request = new RequestData( [] );

		$user = $this->makeMockUser();
		RequestContext::getMain()->setUser( $user );

		$response = $this->executeHandlerAndGetBodyData( $handler, $request );
		$this->assertSame( $expectedResponse, $response );
	}
}

// Returns a list of page revisions by the current logged-in user
// There is a stable chronological order allowing the client to request
// the next or previous segments such that the client will eventually receive all contributions
// Returned list must segmented based on a LIMIT
// Response object must be JSON
// Response object must contain the following fields:
