<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Content\WikitextContent;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\ChangeTags\RestrictedTagTestTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiQueryDeletedrevs
 */
class ApiQueryDeletedrevsTest extends ApiTestCase {
	use MockAuthorityTrait;
	use RestrictedTagTestTrait;

	private Title $testTitle;
	private User $user;

	protected function setUp(): void {
		parent::setUp();

		$this->user = $this->getTestUser( [ 'sysop', 'deletedhistory' ] )->getUser();
		$this->setGroupPermissions( 'sysop', 'deleterevision', true );

		$page = $this->getNonexistingTestPage( 'TestPage' );
		$page->doUserEditContent(
			new WikitextContent( 'Test content for SHA1 test' ),
			$this->user,
			'Test edit'
		);

		$this->testTitle = $page->getTitle();

		$this->deletePage( $page, 'Testing', $this->user );
	}

	public function testDeletedRevisionsWithSha1() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'deletedrevisions',
			'titles' => $this->testTitle->getPrefixedText(),
			'drvprop' => 'sha1|ids|content',
			'drvlimit' => '1',
			'drvslots' => 'main',
		], null, false, $this->user );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$page = reset( $result[0]['query']['pages'] );

		$this->assertArrayHasKey( 'deletedrevisions', $page );
		$rev = reset( $page['deletedrevisions'] );

		$this->assertArrayHasKey( 'sha1', $rev );
		$this->assertSame( '9a7af48d6f38ebdaa3fef9cb192d3f76e16a995f', $rev['sha1'] );
	}

	public function testDeletedRevisionsWithoutSha1() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'deletedrevisions',
			'titles' => $this->testTitle->getPrefixedText(),
			'drvprop' => 'ids',
			'drvlimit' => '1',
		], null, false, $this->user );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$page = reset( $result[0]['query']['pages'] );

		$this->assertArrayHasKey( 'deletedrevisions', $page );
		$rev = reset( $page['deletedrevisions'] );

		$this->assertArrayNotHasKey( 'sha1', $rev );
	}

	/** @dataProvider provideTagFilter */
	public function testTagFilter(
		array $authorityRights,
		string $tagFilter,
		bool $shouldTagFilterFindRevision
	): void {
		$testPage = $this->getExistingTestPage();
		$revId = $testPage->getLatest();

		$this->getServiceContainer()->getChangeTagsStore()->addTags( [ 'mw-private-test' ], null, $revId );
		$this->setRestrictedTags( [ 'mw-private-test' => 'patrol' ] );

		$this->deletePage( $testPage );

		$params = [
			'action' => 'query',
			'list' => 'deletedrevs',
			'drprop' => 'ids|tags',
			'drtag' => $tagFilter,
		];

		[ $result ] = $this->doApiRequest(
			$params,
			null,
			false,
			$this->mockRegisteredAuthorityWithPermissions( $authorityRights )
		);
		if ( $shouldTagFilterFindRevision ) {
			$this->assertCount( 1, $result['query']['deletedrevs'] );
			$this->assertArrayHasKey( 'revisions', $result['query']['deletedrevs'][0] );
			$this->assertCount( 1, $result['query']['deletedrevs'][0]['revisions'] );
			$this->assertArrayHasKey( 'tags', $result['query']['deletedrevs'][0]['revisions'][0] );
			$this->assertContains( 'mw-private-test', $result['query']['deletedrevs'][0]['revisions'][0]['tags'] );
		} else {
			$this->assertCount( 0, $result['query']['deletedrevs'] );
		}
	}

	public static function provideTagFilter(): array {
		return [
			'Filtering for non-existent tag' => [
				'authorityRights' => [ 'patrol', 'deletedhistory' ],
				'tagFilter' => 'mw-test-non-existing-tag',
				'shouldTagFilterFindRevision' => false,
			],
			'Filtering for private tag the user cannot see' => [
				'authorityRights' => [ 'deletedhistory' ],
				'tagFilter' => 'mw-private-test',
				'shouldTagFilterFindRevision' => false,
			],
			'Filtering for private tag the user can see' => [
				'authorityRights' => [ 'patrol', 'deletedhistory' ],
				'tagFilter' => 'mw-private-test',
				'shouldTagFilterFindRevision' => true,
			],
		];
	}
}
