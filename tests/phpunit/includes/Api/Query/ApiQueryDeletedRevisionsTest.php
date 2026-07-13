<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\ChangeTags\RestrictedTagTestTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @group API
 * @group Database
 * @covers \MediaWiki\Api\ApiQueryDeletedRevisions
 */
class ApiQueryDeletedRevisionsTest extends ApiTestCase {
	use MockAuthorityTrait;
	use RestrictedTagTestTrait;

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
			'prop' => 'deletedrevisions',
			'titles' => $testPage->getTitle()->getPrefixedText(),
			'drvprop' => 'ids|tags',
			'drvtag' => $tagFilter,
		];

		[ $result ] = $this->doApiRequest(
			$params,
			null,
			false,
			$this->mockRegisteredAuthorityWithPermissions( $authorityRights )
		);
		if ( $shouldTagFilterFindRevision ) {
			$this->assertContains( 'mw-private-test', $this->getRevisionTags( $result ) );
		} else {
			$this->assertArrayNotHasKey( 'deletedrevisions', $result['query']['pages'][-1] );
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

	private function getRevisionTags( array $result ): array {
		$tagLists = [];
		foreach ( $result['query']['pages'] as $page ) {
			foreach ( $page['deletedrevisions'] as $revision ) {
				$tagLists[] = $revision['tags'] ?? [];
			}
		}

		return array_merge( ...$tagLists );
	}
}
