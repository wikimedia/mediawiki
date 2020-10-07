<?php

namespace MediaWiki\Tests\Rest\Handler;

use CommentStoreComment;
use MediaWiki\Rest\Handler\UserContributionsHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\ContributionsSegment;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserNameUtils;
use PHPUnit\Framework\MockObject\MockObject;
use RequestContext;
use User;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\UserContributionsHandler
 */
class UserContributionsHandlerTest extends \MediaWikiUnitTestCase {

	use HandlerTestTrait;

	private const DEFAULT_LIMIT = 20;

	private function makeFakeRevisions( UserIdentity $user, int $numRevs, int $limit, int $segment = 1 ) {
		$revisions = [];
		$title = $this->makeMockTitle( 'Main_Page', [ 'id' => 1 ] );
		for ( $i = $numRevs; $i >= 1; $i-- ) {
			$rev = new MutableRevisionRecord( $title );
			$ogTimestamp = '2020010100000';
			$rev->setId( $i );
			$rev->setSize( 256 );
			$rev->setComment( CommentStoreComment::newUnsavedComment( 'Edit ' . $i ) );
			$rev->setTimestamp( $ogTimestamp . $i );
			$revisions[] = $rev;
		}

		return array_slice( $revisions, $segment - 1, $limit );
	}

	/**
	 * @param int $numRevisions
	 * @param array $tags
	 * @param array $deltas
	 * @param array $flags
	 *
	 * @return ContributionsLookup|MockObject
	 */
	private function newContributionsLookup( $numRevisions = 5, $tags = [], $deltas = [], $flags = [] ) {
		/** @var MockObject|ContributionsLookup $mockContributionsLookup */
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributions' ]
		);

		$user = new UserIdentityValue( 0, 'test', 0 );
		$fakeRevisions = $this->makeFakeRevisions( $user, $numRevisions, 2 );
		$fakeSegment = $this->makeSegment( $fakeRevisions, $tags, $deltas, $flags );
		$mockContributionsLookup->method( 'getContributions' )->willReturn( $fakeSegment );

		return $mockContributionsLookup;
	}

	/**
	 * Returns a mock ContributionLookup that asserts getContributions()
	 * is called with the same params that were originally passed into the request.
	 * @param RequestInterface $request
	 *
	 * @return ContributionsLookup|MockObject
	 */
	private function newContributionsLookupForRequest( RequestInterface $request, $target, $performer ) {
		$limit = $request->getQueryParams()['limit'] ?? self::DEFAULT_LIMIT;
		$segment = $request->getQueryParams()['segment'] ?? '';
		$tag = $request->getQueryParams()['tag'] ?? null;

		$fakeRevisions = $this->makeFakeRevisions( $target, 5, $limit );
		$fakeSegment = $this->makeSegment( $fakeRevisions );

		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributions' ]
		);
		$mockContributionsLookup->method( 'getContributions' )
			->willReturnCallback(
				function (
					$actualTarget,
					$actualLimit,
					$actualPerformer,
					$actualSegment,
					$actualTag
				) use ( $target, $limit, $performer, $segment, $tag, $fakeSegment ) {
					$this->assertSame( $target->getName(), $actualTarget->getName() );
					$this->assertSame( $limit, $actualLimit );
					$this->assertSame( $performer->getName(), $actualPerformer->getName() );
					$this->assertSame( $segment, $actualSegment );
					$this->assertSame( $tag, $actualTag );
					return $fakeSegment;
				}
			);

		return $mockContributionsLookup;
	}

	/**
	 * @param ContributionsLookup $contributionsLookup
	 *
	 * @return UserContributionsHandler
	 */
	private function newHandler( ContributionsLookup $contributionsLookup = null ) {
		if ( !$contributionsLookup ) {
			$contributionsLookup = $this->newContributionsLookup();
		}

		$mockUserFactory = $this->createNoOpMock( UserFactory::class,
			[ 'newFromName' ]
		);
		$mockUserFactory->method( 'newFromName' )
			->willReturnCallback( [ $this, 'makeMockUser' ] );

		$mockUserNameUtils = $this->createNoOpMock( UserNameUtils::class,
			[ 'isIP' ]
		);
		$mockUserNameUtils->method( 'isIP' )
			->willReturnCallback( function ( $name ) {
				return $name === '127.0.0.1';
			} );

		return new UserContributionsHandler(
			$contributionsLookup,
			$mockUserFactory,
			$mockUserNameUtils
		);
	}

	private function makeSegment( $revisions, array $tags = [], $deltas = [], array $flags = [] ) {
		if ( $revisions !== [] ) {
			$latestRevision = $revisions[ count( $revisions ) - 1 ];
			$earliestRevision = $revisions[0];
			$before = 'before|' . $latestRevision->getTimestamp();
			$after = 'after|' . $earliestRevision->getTimestamp();
			return new ContributionsSegment( $revisions, $tags, $before, $after, $deltas, $flags );
		}
		return new ContributionsSegment( $revisions, $tags, null, null, $deltas, $flags );
	}

	public function provideValidQueryParameters() {
		yield [ [] ];
		yield [ [ 'limit' => self::DEFAULT_LIMIT ] ];
		yield [ [ 'tag' => 'test', 'limit' => 7 ] ];
		yield [ [ 'segment' => 'before|20200101000005' ] ];
		yield [ [ 'segment' => 'after|20200101000001' ] ];
	}

	/**
	 * @param array $queryParams
	 * @dataProvider provideValidQueryParameters
	 */
	public function testThatParametersAreHandledCorrectlyForMeEndpoint( $queryParams ) {
		$request = new RequestData( [ 'queryParams' => $queryParams ] );
		$user = $this->makeMockUser( 'Arnold' );

		$mockContributionsLookup = $this->newContributionsLookupForRequest( $request, $user, $user );
		$handler = $this->newHandler( $mockContributionsLookup );

		RequestContext::getMain()->setUser( $user );
		$response = $this->executeHandler( $handler, $request, [ 'mode' => 'me' ] );

		$this->assertSame( 200, $response->getStatusCode() );
	}

	/**
	 * @param array $queryParams
	 * @dataProvider provideValidQueryParameters
	 */
	public function testThatParametersAreHandledCorrectlyForUserEndpoint( $queryParams ) {
		$target = new UserIdentityValue( 7, 'Test', 7 );
		$performer = $this->makeMockUser( 'Arnold' );

		$pathParams = [ 'name' => $target->getName() ];
		$request = new RequestData( [ 'queryParams' => $queryParams, 'pathParams' => $pathParams ] );

		$mockContributionsLookup = $this->newContributionsLookupForRequest( $request, $target, $performer );
		$handler = $this->newHandler( $mockContributionsLookup );

		RequestContext::getMain()->setUser( $performer );
		$response = $this->executeHandler( $handler, $request, [ 'mode' => 'name' ] );

		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testThatAnonymousUserReturns401() {
		$handler = $this->newHandler();
		$request = new RequestData( [] );

		$user = $this->makeMockUser( '127.0.0.1' );
		RequestContext::getMain()->setUser( $user );

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-permission-denied-anon' ), 401 )
		);
		$this->executeHandler( $handler, $request, [ 'mode' => 'me' ] );
	}

	public function testThatInvalidUserReturns400() {
		$handler = $this->newHandler();
		$request = new RequestData( [ 'pathParams' => [ 'name' => 'B/A/D' ] ] );

		$user = $this->makeMockUser( 'Arnold' );
		RequestContext::getMain()->setUser( $user );

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-invalid-user' ), 400 )
		);
		$this->executeHandler( $handler, $request, [ 'mode' => 'user' ] );
	}

	public function testThatUnknownUserReturns404() {
		$handler = $this->newHandler();
		$request = new RequestData( [ 'pathParams' => [ 'name' => 'UNKNOWN' ] ] );

		$user = $this->makeMockUser( 'Arnold' );
		RequestContext::getMain()->setUser( $user );

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-nonexistent-user' ), 404 )
		);
		$this->executeHandler( $handler, $request, [ 'mode' => 'user' ] );
	}

	public function testThatIpUserReturns200() {
		$handler = $this->newHandler();
		$request = new RequestData( [ 'pathParams' => [ 'name' => '127.0.0.1' ] ] );

		$user = $this->makeMockUser( 'Arnold' );
		RequestContext::getMain()->setUser( $user );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [ 'mode' => 'user' ] );
		$this->assertArrayHasKey( 'revisions', $data );
	}

	public function makeMockUser( $name ) {
		$isIP = ( $name === '127.0.0.1' );
		$isBad = ( $name === 'B/A/D' );
		$isUnknown = ( $name === 'UNKNOWN' );
		$isAnon = $isIP || $isBad || $isUnknown;

		if ( $isBad ) {
			// per the contract of UserFactory::newFromName
			return false;
		}

		$user = $this->createNoOpMock(
			User::class,
			[ 'isAnon', 'getId', 'getName', 'isRegistered', 'isLoggedIn' ]
		);
		$user->method( 'isAnon' )->willReturn( $isAnon );
		$user->method( 'isRegistered' )->willReturn( !$isAnon );
		$user->method( 'isLoggedIn' )->willReturn( !$isAnon );
		$user->method( 'getId' )->willReturn( $isAnon ? 0 : 7 );
		$user->method( 'getName' )->willReturn( $name );
		return $user;
	}

	public function provideThatResponseConformsToSchema() {
		$basePath = 'https://wiki.example.com/rest/me/contributions';
		yield [ 0,
			[],
			[],
			[ 'newest' => true, 'oldest' => true ],
			[],
			[
				'older' => null,
				'newer' => $basePath . '?limit=20',
				'latest' => $basePath . '?limit=20',
				'revisions' => []
			]
		];
		yield [ 0,
			[],
			[],
			[ 'newest' => true, 'oldest' => true ],
			[ 'tag' => 'test' ],
			[
				'older' => null,
				'newer' => $basePath . '?limit=20&tag=test',
				'latest' => $basePath . '?limit=20&tag=test',
				'revisions' => []
			]
		];
		yield [ 1,
			[ 1 => [ 'frob' ] ],
			[ 1 => 256 ],
			[ 'newest' => true, 'oldest' => true ],
			[ 'limit' => 7 ],
			[
				'older' => null,
				'newer' => $basePath . '?limit=7&segment=after%7C20200101000001',
				'latest' => $basePath . '?limit=7',
				'revisions' => [
					[
						'id' => 1,
						'comment' => 'Edit 1',
						'timestamp' => '2020-01-01T00:00:01Z',
						'delta' => 256,
						'size' => 256,
						'tags' => [ 'frob' ],
						'page' => [
							'id' => 1,
							'key' => 'Main_Page',
							'title' => 'Main Page'
						]
					]
				]
			]
		];
		yield [ 5,
			[ 5 => [ 'frob', 'nitz' ] ],
			[ 1 => 256, 2 => 256, 3 => 256, 4 => null, 5 => 256 ],
			[ 'newest' => true ],
			[ 'tag' => 'test' ],
			[
				'older' => $basePath . '?limit=20&tag=test&segment=before%7C20200101000004',
				'newer' => $basePath . '?limit=20&tag=test&segment=after%7C20200101000005',
				'latest' => $basePath . '?limit=20&tag=test',
				'revisions' => [
					[
						'id' => 5,
						'comment' => 'Edit 5',
						'timestamp' => '2020-01-01T00:00:05Z',
						'delta' => 256,
						'size' => 256,
						'tags' => [ 'frob', 'nitz' ],
						'page' => [
							'id' => 1,
							'key' => 'Main_Page',
							'title' => 'Main Page'
						]
					],
					[
						'id' => 4,
						'comment' => 'Edit 4',
						'timestamp' => '2020-01-01T00:00:04Z',
						'delta' => null,
						'size' => 256,
						'tags' => [],
						'page' => [
							'id' => 1,
							'key' => 'Main_Page',
							'title' => 'Main Page'
						]
					]
				]
			]
		];
	}

	/**
	 * @dataProvider provideThatResponseConformsToSchema
	 */
	public function testThatResponseConformsToSchema(
		$numRevisions,
		$tags,
		$deltas,
		$flags,
		$query,
		$expectedResponse
	) {
		$lookup = $this->newContributionsLookup( $numRevisions, $tags, $deltas, $flags );
		$handler = $this->newHandler( $lookup );
		$request = new RequestData( [ 'queryParams' => $query ] );

		$user = $this->makeMockUser( 'Arnold' );
		RequestContext::getMain()->setUser( $user );

		$config = [ 'path' => '/me/contributions', 'mode' => 'me' ];

		$response = $this->executeHandlerAndGetBodyData( $handler, $request, $config );
		$this->assertSame( $expectedResponse, $response );
	}
}
