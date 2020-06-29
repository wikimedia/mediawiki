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
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;
use RequestContext;
use User;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\UserContributionsHandler
 */
class UserContributionsHandlerTest extends \MediaWikiUnitTestCase {

	use HandlerTestTrait;

	private const DEFAULT_LIMIT = 2;

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

	private function newHandler( $numRevisions = 5, $tags = [], $deltas = [], $flags = [] ) {
		/** @var MockObject|ContributionsLookup $mockContributionsLookup */
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributions' ]
		);
		$user = new UserIdentityValue( 0, 'test', 0 );
		$fakeRevisions = $this->makeFakeRevisions( $user, $numRevisions, 2 );
		$fakeSegment = $this->makeSegment( $fakeRevisions, $tags, $deltas, $flags );
		$mockContributionsLookup->method( 'getContributions' )->willReturn( $fakeSegment );
		$handler = new UserContributionsHandler( $mockContributionsLookup );
		return $handler;
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

	public function provideTestThatParametersAreHandledCorrectly() {
		yield [
			new RequestData(
				[
					'queryParams' => [
						'segment' => 'before|20200101000005',
						'limit' => self::DEFAULT_LIMIT
					]
				]
			)
		];
		yield [ new RequestData(
			[
				'queryParams' => [
					'segment' => 'after|20200101000001',
					'limit' => self::DEFAULT_LIMIT
				]
			]
		) ];
		yield [ new RequestData(
			[ 'queryParams' => [ 'limit' => self::DEFAULT_LIMIT ] ]
		) ];
	}

	/**
	 * @param RequestInterface $request
	 * @dataProvider provideTestThatParametersAreHandledCorrectly
	 */
	public function testThatParametersAreHandledCorrectly( RequestInterface $request ) {
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getContributions' ]
		);
		$user = $this->makeMockUser( false );
		RequestContext::getMain()->setUser( $user );

		$fakeRevisions = $this->makeFakeRevisions( $user, 5, self::DEFAULT_LIMIT );
		$fakeSegment = $this->makeSegment( $fakeRevisions );

		$limit = $request->getQueryParams()['limit'] ?? self::DEFAULT_LIMIT;
		$segment = $request->getQueryParams()['segment'] ?? null;
		$mockContributionsLookup->method( 'getContributions' )
			->with( $user, $limit, $user, $segment )
			->willReturn( $fakeSegment );

		$handler = new UserContributionsHandler( $mockContributionsLookup );

		$response = $this->executeHandler( $handler, $request );

		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testThatAnonymousUserReturns401() {
		$handler = $this->newHandler();
		$request = new RequestData( [] );
		RequestContext::getMain()->setUser( new User() );

		$user = $this->makeMockUser( true );
		RequestContext::getMain()->setUser( $user );

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-permission-denied-anon' ), 401 )
		);
		$this->executeHandler( $handler, $request );
	}

	private function makeMockUser( $anon ) {
		$user = $this->createNoOpMock( User::class, [ 'isAnon' ] );
		$user->method( 'isAnon' )->willReturn( $anon );
		return $user;
	}

	public function provideThatResponseConformsToSchema() {
		$basePath = 'https://wiki.example.com/rest/me/contributions';
		yield [ 0,
			[],
			[],
			[ 'newest' => true, 'oldest' => true ],
			[
				'older' => null,
				'newer' => $basePath . '?limit=20',
				'latest' => $basePath . '?limit=20',
				'revisions' => []
			]
		];
		yield [ 1,
			[ 1 => [ 'frob' ] ],
			[ 1 => 256 ],
			[ 'newest' => true, 'oldest' => true ],
			[
				'older' => null,
				'newer' => $basePath . '?limit=20&segment=after%7C20200101000001',
				'latest' => $basePath . '?limit=20',
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
			[
				'older' => $basePath . '?limit=20&segment=before%7C20200101000004',
				'newer' => $basePath . '?limit=20&segment=after%7C20200101000005',
				'latest' => $basePath . '?limit=20',
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
	public function testThatResponseConformsToSchema( $numRevisions, $tags, $deltas, $flags, $expectedResponse ) {
		$handler = $this->newHandler( $numRevisions, $tags, $deltas, $flags );
		$request = new RequestData( [] );

		$user = $this->makeMockUser( false );
		RequestContext::getMain()->setUser( $user );

		$config = [ 'path' => '/me/contributions' ];

		$response = $this->executeHandlerAndGetBodyData( $handler, $request, $config );
		$this->assertSame( $expectedResponse, $response );
	}
}

// Returns a list of page revisions by the current logged-in user
// There is a stable chronological order allowing the client to request
// the next or previous segments such that the client will eventually receive all contributions
// Returned list must segmented based on a LIMIT
// Response object must be JSON
// Response object must contain the following fields:
