<?php

namespace MediaWiki\Tests\Rest\Handler;

use CommentStoreComment;
use MediaWiki\Rest\Handler\UserContributionsHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
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

	private function newHandler( $numRevisions = 5 ) {
		/** @var MockObject|ContributionsLookup $mockContributionsLookup */
		$mockContributionsLookup = $this->createNoOpMock( ContributionsLookup::class,
			[ 'getRevisionsByUser', 'getParentRevisionSizes' ]
		);
		$user = new UserIdentityValue( 0, 'test', 0 );
		$fakeRevisions = $this->makeFakeRevisions( $user, $numRevisions, 2 );
		$fakeSegment = $this->makeSegment( $fakeRevisions );
		$mockContributionsLookup->method( 'getRevisionsByUser' )->willReturn( $fakeSegment );
		$mockContributionsLookup->method( 'getParentRevisionSizes' )->willReturn( [] );
		$handler = new UserContributionsHandler( $mockContributionsLookup );
		return $handler;
	}

	private function makeSegment( $revisions ) {
		if ( $revisions !== [] ) {
			return new ContributionsSegment( $revisions, null, null );
		}
		return new ContributionsSegment( $revisions, null, null );
	}

	public function testThatAnonymousUserReturns401() {
		$handler = $this->newHandler();
		$request = new RequestData( [] );

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-permission-denied-anon' ), 401 )
		);
		$response = $this->executeHandler( $handler, $request );
		$this->assertSame( 401, $response->getStatusCode() );
	}

	private function makeMockUser() {
		$user = $this->createNoOpMock( User::class, [ 'isAnon' ] );
		$user->method( 'isAnon' )->willReturn( false );
		return $user;
	}

	public function provideThatResponseConformsToSchema() {
		$basePath = 'https://wiki.example.com/rest/coredev/v0/me/contributions';
		yield [ 0,
			[
				'revisions' => []
			]
		];
		yield [ 1,
			[
				'revisions' => [
					[
						'id' => 1,
						'comment' => 'Edit 1',
						'timestamp' => '2020-01-01T00:00:01Z',
						'size' => 256,
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
			[
				'revisions' => [
					[
						'id' => 5,
						'comment' => 'Edit 5',
						'timestamp' => '2020-01-01T00:00:05Z',
						'size' => 256,
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
						'size' => 256,
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
