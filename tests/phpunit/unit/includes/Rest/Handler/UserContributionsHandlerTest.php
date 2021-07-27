<?php

namespace MediaWiki\Tests\Rest\Handler;

use CommentStoreComment;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler\UserContributionsHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\ContributionsSegment;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Message;
use MockTitleTrait;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\UserContributionsHandler
 */
class UserContributionsHandlerTest extends \MediaWikiUnitTestCase {
	use DummyServicesTrait;
	use HandlerTestTrait;
	use MockTitleTrait;

	private const DEFAULT_LIMIT = 20;

	private function makeFakeRevisions( int $numRevs, int $limit, int $segment = 1 ) {
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
	 * @param string[] $tags
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
		$fakeRevisions = $this->makeFakeRevisions( $numRevisions, 2 );
		foreach ( $tags as $revId => $tagArray ) {
			$tags[ $revId ] = [];
			foreach ( $tagArray as $name ) {
				$mockMessage = $this->createNoOpMock( Message::class, [ 'parse', 'getKey' ] );
				$mockMessage->method( 'parse' )->willReturn( "<i>$name</i>" );
				$mockMessage->method( 'getKey' )->willReturn( "tag-$name" );
				$tags[ $revId ][ $name ] = $mockMessage;
			}
		}
		$fakeSegment = $this->makeSegment( $fakeRevisions, $tags, $deltas, $flags );
		$mockContributionsLookup->method( 'getContributions' )->willReturn( $fakeSegment );

		return $mockContributionsLookup;
	}

	/**
	 * Returns a mock ContributionLookup that asserts getContributions()
	 * is called with the same params that were originally passed into the request.
	 * @param RequestInterface $request
	 * @param UserIdentity $target
	 * @param Authority $performer
	 * @return ContributionsLookup|MockObject
	 */
	private function newContributionsLookupForRequest(
		RequestInterface $request,
		UserIdentity $target,
		Authority $performer
	) {
		$limit = $request->getQueryParams()['limit'] ?? self::DEFAULT_LIMIT;
		$segment = $request->getQueryParams()['segment'] ?? '';
		$tag = $request->getQueryParams()['tag'] ?? null;

		$fakeRevisions = $this->makeFakeRevisions( 5, $limit );
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
					$this->assertTrue(
						$performer->getUser()->equals( $actualPerformer->getUser() )
					);
					$this->assertSame( $segment, $actualSegment );
					$this->assertSame( $tag, $actualTag );
					return $fakeSegment;
				}
			);

		return $mockContributionsLookup;
	}

	/**
	 * @param ContributionsLookup|null $contributionsLookup
	 *
	 * @return UserContributionsHandler
	 */
	private function newHandler( ContributionsLookup $contributionsLookup = null ) {
		if ( !$contributionsLookup ) {
			$contributionsLookup = $this->newContributionsLookup();
		}

		return new UserContributionsHandler(
			$contributionsLookup,
			$this->getDummyUserNameUtils()
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
		$performer = $this->mockRegisteredUltimateAuthority();
		$performingUser = $performer->getUser();
		$validatedParams = [
			'user' => null,
			'limit' => $queryParams['limit'] ?? self::DEFAULT_LIMIT,
			'tag' => $queryParams['tag'] ?? null,
			'segment' => $queryParams['segment'] ?? '',
		];
		$mockContributionsLookup = $this->newContributionsLookupForRequest( $request, $performingUser, $performer );
		$handler = $this->newHandler( $mockContributionsLookup );

		$response = $this->executeHandler( $handler, $request, [ 'mode' => 'me' ],
			[], $validatedParams, [], $performer );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	/**
	 * @param array $queryParams
	 * @dataProvider provideValidQueryParameters
	 */
	public function testThatParametersAreHandledCorrectlyForUserEndpoint( $queryParams ) {
		$username = 'Test';
		$target = new UserIdentityValue( 7, $username );
		$performer = $this->mockRegisteredUltimateAuthority();
		$request = new RequestData( [
			'pathParams' => [ 'user' => $target->getName() ],
			'queryParams' => $queryParams ]
		);
		$validatedParams =
			[
				'user' => $target,
				'limit' => $queryParams['limit'] ?? self::DEFAULT_LIMIT,
				'tag' => $queryParams['tag'] ?? null,
				'segment' => $queryParams['segment'] ?? '',
			];
		$mockContributionsLookup = $this->newContributionsLookupForRequest( $request, $target, $performer );
		$handler = $this->newHandler( $mockContributionsLookup );

		$response = $this->executeHandler( $handler, $request, [ 'mode' => 'user' ], [], $validatedParams, [],
			$performer );

		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testThatAnonymousUserReturns401() {
		$handler = $this->newHandler();
		$request = new RequestData( [] );
		// UserDef transforms parameter name to ip
		$validatedParams = [
			'ip' => new UserIdentityValue( 0, '127.0.0.1' ),
			'limit' => self::DEFAULT_LIMIT,
			'tag' => null,
			'segment' => ''
		];
		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-permission-denied-anon' ), 401 )
		);
		$this->executeHandler( $handler, $request, [ 'mode' => 'me' ], [], $validatedParams );
	}

	public function testThatUnknownUserReturns404() {
		$handler = $this->newHandler();
		$username = 'UNKNOWN';
		$request = new RequestData( [ 'pathParams' => [ 'user' => $username ] ] );
		$validatedParams = [
			'user' => new UserIdentityValue( 0, $username ),
			'limit' => self::DEFAULT_LIMIT,
			'tag' => null,
			'segment' => ''
		];

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-nonexistent-user' ), 404 )
		);
		$this->executeHandler( $handler, $request, [ 'mode' => 'user' ], [], $validatedParams );
	}

	public function testThatIpUserReturns200() {
		$handler = $this->newHandler();
		$username = '127.0.0.1';
		$requestData = [ 'pathParams' => [ 'user' => $username ] ];
		$request = new RequestData( $requestData );
		$validatedParams = [
			'user' => new UserIdentityValue( 0, $username ),
			'limit' => self::DEFAULT_LIMIT,
			'tag' => null,
			'segment' => ''
		];

		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [ 'mode' => 'user' ], [], $validatedParams );
		$this->assertArrayHasKey( 'contributions', $data );
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
				'contributions' => []
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
				'contributions' => []
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
				'contributions' => [
					[
						'id' => 1,
						'comment' => 'Edit 1',
						'timestamp' => '2020-01-01T00:00:01Z',
						'delta' => 256,
						'size' => 256,
						'tags' => [ [ 'name' => 'frob', 'description' => '<i>frob</i>' ] ],
						'type' => 'revision',
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
				'contributions' => [
					[
						'id' => 5,
						'comment' => 'Edit 5',
						'timestamp' => '2020-01-01T00:00:05Z',
						'delta' => 256,
						'size' => 256,
						'tags' => [
							[ 'name' => 'frob', 'description' => '<i>frob</i>' ],
							[ 'name' => 'nitz', 'description' => '<i>nitz</i>' ]
						],
						'type' => 'revision',
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
						'type' => 'revision',
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

		$validatedParams = [
			'user' => null,
			'limit' => $query['limit'] ?? self::DEFAULT_LIMIT,
			'tag' => $query['tag'] ?? null,
			'segment' => $query['segment'] ?? '',
		];
		$config = [ 'path' => '/me/contributions', 'mode' => 'me' ];
		$response = $this->executeHandlerAndGetBodyData( $handler, $request, $config, [], $validatedParams, [],
			$this->mockRegisteredUltimateAuthority() );
		$this->assertSame( $expectedResponse, $response );
	}
}
