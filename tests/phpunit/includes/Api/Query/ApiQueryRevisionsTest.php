<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiQueryRevisions;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\ChangeTags\RestrictedTagTestTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserIdentityValue;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiQueryRevisions
 */
class ApiQueryRevisionsTest extends ApiTestCase {
	use TempUserTestTrait;
	use RestrictedTagTestTrait;
	use MockAuthorityTrait;

	/**
	 * @group medium
	 */
	public function testContentComesWithContentModelAndFormat() {
		$pageName = 'Help:' . __METHOD__;
		$page = $this->getExistingTestPage( $pageName );
		$user = $this->getTestUser()->getUser();
		$page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( 'Some text' ) )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'inserting content' ) );

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvprop' => 'content',
			'rvslots' => 'main',
		] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach ( $apiResult[0]['query']['pages'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach ( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( 'slots', $revision );
				$this->assertArrayHasKey( 'main', $revision['slots'] );
				$this->assertArrayHasKey( 'contentformat', $revision['slots']['main'],
					'contentformat should be included when asking content so client knows how to interpret it'
				);
				$this->assertArrayHasKey( 'contentmodel', $revision['slots']['main'],
					'contentmodel should be included when asking content so client knows how to interpret it'
				);
			}
		}
	}

	/**
	 * @group medium
	 */
	public function testRevisionMadeByTempUser() {
		$this->enableAutoCreateTempUser();
		$tempUser = new UserIdentityValue( 1236764321, '~1' );

		$title = $this->getNonexistingTestPage( 'TestPage1' )->getTitle();
		$this->editPage(
			$title,
			'Some Content',
			'Create Page',
			NS_MAIN,
			new UltimateAuthority( $tempUser )
		);

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => 'TestPage1'
		] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'temp', $apiResult[0]['query']['pages'][1]['revisions'][0] );
		$this->assertTrue( $apiResult[0]['query']['pages'][1]['revisions'][0]['temp'] );
	}

	/**
	 * @group medium
	 */
	public function testResolvesPrevNextInDiffto() {
		$pageName = 'Help:' . __METHOD__;
		$page = $this->getExistingTestPage( $pageName );
		$user = $this->getTestUser()->getUser();

		$revRecord = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( 'Some text' ) )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'inserting more content' ) );

		[ $rvDiffToPrev ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvdiffto' => 'prev',
		] );

		$this->assertSame(
			$revRecord->getId(),
			$rvDiffToPrev['query']['pages'][$page->getId()]['revisions'][0]['revid']
		);
		$this->assertSame(
			$revRecord->getId(),
			$rvDiffToPrev['query']['pages'][$page->getId()]['revisions'][0]['diff']['to']
		);
		$this->assertSame(
			$revRecord->getParentId(),
			$rvDiffToPrev['query']['pages'][$page->getId()]['revisions'][0]['diff']['from']
		);

		[ $rvDiffToNext ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvdiffto' => 'next',
			'rvdir' => 'newer'
		] );

		$this->assertSame(
			$revRecord->getParentId(),
			$rvDiffToNext['query']['pages'][$page->getId()]['revisions'][0]['revid']
		);
		$this->assertSame(
			$revRecord->getId(),
			$rvDiffToNext['query']['pages'][$page->getId()]['revisions'][0]['diff']['to']
		);
		$this->assertSame(
			$revRecord->getParentId(),
			$rvDiffToNext['query']['pages'][$page->getId()]['revisions'][0]['diff']['from']
		);
	}

	/**
	 * @dataProvider provideSectionNewTestCases
	 * @param string $pageContent
	 * @param string $expectedSectionContent
	 * @group medium
	 */
	public function testSectionNewReturnsEmptyContentForPageWithSection(
		$pageContent,
		$expectedSectionContent
	) {
		$pageName = 'Help:' . __METHOD__;
		$page = $this->getExistingTestPage( $pageName );
		$user = $this->getTestUser()->getUser();
		$revRecord = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( $pageContent ) )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'inserting content' ) );

		[ $response ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'revids' => $revRecord->getId(),
			'rvprop' => 'content|ids',
			'rvslots' => 'main',
			'rvsection' => 'new'
		] );

		$this->assertSame(
			$expectedSectionContent,
			$response['query']['pages'][$page->getId()]['revisions'][0]['slots']['main']['content']
		);
	}

	public static function provideSectionNewTestCases() {
		yield 'page with existing section' => [
			"==A section==\ntext",
			''
		];
		yield 'page with no sections' => [
			'This page has no sections',
			'This page has no sections'
		];
	}

	public function testRvPropTagsHidesRestrictedTagFromUnprivilegedViewer(): void {
		$testPage = $this->getExistingTestPage();
		$revId = $testPage->getLatest();
		$this->getServiceContainer()->getChangeTagsStore()
			->addTags( [ 'mw-private-secret' ], null, $revId );
		$this->setRestrictedTags( [ 'mw-private-secret' => 'viewsuppressed' ] );

		$params = [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $testPage->getTitle()->getPrefixedText(),
			'rvprop' => 'ids|tags',
		];

		[ $unprivileged ] = $this->doApiRequest(
			$params, null, false, $this->mockRegisteredAuthorityWithoutPermissions( [ 'viewsuppressed' ] )
		);
		$this->assertNotContains( 'mw-private-secret', $this->getRevisionTags( $unprivileged ) );

		[ $privileged ] = $this->doApiRequest(
			$params, null, false, $this->mockRegisteredAuthorityWithPermissions( [ 'viewsuppressed' ] )
		);
		$this->assertContains( 'mw-private-secret', $this->getRevisionTags( $privileged ) );
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

		$params = [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $testPage->getTitle()->getPrefixedText(),
			'rvprop' => 'ids|tags',
			'rvtag' => $tagFilter,
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
			$this->assertArrayNotHasKey( 'revisions', $result['query']['pages'][$testPage->getId()] );
		}
	}

	public static function provideTagFilter(): array {
		return [
			'Filtering for non-existent tag' => [
				'authorityRights' => [ 'patrol' ],
				'tagFilter' => 'mw-test-non-existing-tag',
				'shouldTagFilterFindRevision' => false,
			],
			'Filtering for private tag the user cannot see' => [
				'authorityRights' => [],
				'tagFilter' => 'mw-private-test',
				'shouldTagFilterFindRevision' => false,
			],
			'Filtering for private tag the user can see' => [
				'authorityRights' => [ 'patrol' ],
				'tagFilter' => 'mw-private-test',
				'shouldTagFilterFindRevision' => true,
			],
		];
	}

	/**
	 * @dataProvider provideGetCacheMode
	 */
	public function testGetCacheModePrivateWhenTagsRequested( array $prop, string $expected ): void {
		$this->overrideConfigValue( MainConfigNames::RestrictedTagViewRights, [] );

		$revisions = $this->newRevisionsQueryModule( $this->mockRegisteredNullAuthority() );

		$this->assertSame( $expected, $revisions->getCacheMode( [ 'prop' => $prop ] ) );
	}

	public static function provideGetCacheMode(): array {
		return [
			'tags prop is per-viewer private' => [
				'prop' => [ 'ids', 'tags' ],
				'expected' => 'anon-public-user-private',
			],
			'no tags prop stays public' => [
				'prop' => [ 'ids' ],
				'expected' => 'public',
			],
		];
	}

	private function newRevisionsQueryModule( Authority $authority ): ApiQueryRevisions {
		$context = new RequestContext();
		$context->setRequest( new FauxRequest() );
		$context->setAuthority( $authority );

		return ( new ApiMain( $context ) )->getModuleManager()
			->getModule( 'query' )
			->getModuleManager()
			->getModule( 'revisions' );
	}

	private function getRevisionTags( array $result ): array {
		$tagLists = [];
		foreach ( $result['query']['pages'] as $page ) {
			foreach ( $page['revisions'] as $revision ) {
				$tagLists[] = $revision['tags'] ?? [];
			}
		}

		return array_merge( ...$tagLists );
	}
}
