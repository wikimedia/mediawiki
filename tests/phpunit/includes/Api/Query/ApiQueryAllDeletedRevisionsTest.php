<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\ChangeTags\RestrictedTagTestTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;

/**
 * @group API
 * @group Database
 * @covers \MediaWiki\Api\ApiQueryAllDeletedRevisions
 */
class ApiQueryAllDeletedRevisionsTest extends ApiTestCase {
	use MockAuthorityTrait;
	use RestrictedTagTestTrait;

	public function testFromToPrefixParameter() {
		$this->overrideConfigValues( [
			MainConfigNames::CapitalLinks => false,
		] );
		$performer = $this->getTestSysop()->getAuthority();

		$title = Title::makeTitle( NS_MAIN, 'pageM' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->editPage( $page, 'Some text', 'Create', NS_MAIN, $performer );
		$this->deletePage( $page, 'Delete', $performer );

		$userTitle = Title::makeTitle( NS_USER, 'PageU' );
		$userPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $userTitle );
		$this->editPage( $userPage, 'Some text', 'Create', NS_MAIN, $performer );
		$this->deletePage( $userPage, 'Delete', $performer );

		$expectedResult0 = [ 'ns' => $title->getNamespace(), 'title' => $title->getPrefixedDbKey() ];
		$expectedResult1 = [ 'ns' => $userTitle->getNamespace(), 'title' => $userTitle->getPrefixedDbKey() ];

		// Search the page with prefix
		[ $result ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'alldeletedrevisions',
			'adrdir' => 'newer',
			'adrnamespace' => '0|2',
			'adrprefix' => 'page',
		], null, false, $performer );

		$this->assertArrayHasKey( 'query', $result );
		$this->assertArrayHasKey( 'alldeletedrevisions', $result['query'] );
		$this->assertArrayContains( $expectedResult0, $result['query']['alldeletedrevisions'][0] );
		$this->assertArrayContains( $expectedResult1, $result['query']['alldeletedrevisions'][1] );

		// Search the page with from
		[ $result ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'alldeletedrevisions',
			'adrdir' => 'newer',
			'adrnamespace' => '0|2',
			'adrfrom' => 'pageA',
		], null, false, $performer );

		$this->assertArrayHasKey( 'query', $result );
		$this->assertArrayHasKey( 'alldeletedrevisions', $result['query'] );
		$this->assertArrayContains( $expectedResult0, $result['query']['alldeletedrevisions'][0] );
		$this->assertArrayContains( $expectedResult1, $result['query']['alldeletedrevisions'][1] );

		// Search the page with to
		[ $result ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'alldeletedrevisions',
			'adrdir' => 'newer',
			'adrnamespace' => '0|2',
			'adrto' => 'pageZ',
		], null, false, $performer );

		$this->assertArrayHasKey( 'query', $result );
		$this->assertArrayHasKey( 'alldeletedrevisions', $result['query'] );
		$this->assertArrayContains( $expectedResult0, $result['query']['alldeletedrevisions'][0] );
		$this->assertArrayContains( $expectedResult1, $result['query']['alldeletedrevisions'][1] );
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
			'list' => 'alldeletedrevisions',
			'adrprop' => 'ids|tags',
			'adrtag' => $tagFilter,
		];

		[ $result ] = $this->doApiRequest(
			$params,
			null,
			false,
			$this->mockRegisteredAuthorityWithPermissions( $authorityRights )
		);
		if ( $shouldTagFilterFindRevision ) {
			$this->assertCount( 1, $result['query']['alldeletedrevisions'] );
			$this->assertContains(
				'mw-private-test',
				$result['query']['alldeletedrevisions'][0]['revisions'][0]['tags']
			);
		} else {
			$this->assertCount( 0, $result['query']['alldeletedrevisions'] );
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
