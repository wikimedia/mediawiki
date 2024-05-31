<?php

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Pager\ContributionsPager;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;

/**
 * Test class for ContributionsPagerTest handling of revision and archive modes.
 *
 * @group Pager
 * @group Database
 * @covers \MediaWiki\Pager\ContributionsPager
 */
class ContributionsPagerTest extends MediaWikiIntegrationTestCase {

	private function getPagerForTryCreatingRevisionRecord( $isArchive = false ) {
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->method( 'isRevisionRow' )
			->willReturn( true );
		$revisionStore->expects( $this->never() )
			->method( $isArchive ? 'newRevisionFromRow' : 'newRevisionFromArchiveRow' );

		return $this->getMockForAbstractClass(
			ContributionsPager::class,
			[
				$this->createMock( LinkRenderer::class ),
				$this->createMock( LinkBatchFactory::class ),
				$this->createMock( HookContainer::class ),
				$this->createMock( RevisionStore::class ),
				$this->createMock( NamespaceInfo::class ),
				$this->createMock( CommentFormatter::class ),
				$this->createMock( UserFactory::class ),
				new RequestContext(),
				[
					'isArchive' => $isArchive,
				],
				$this->createMock( UserIdentity::class ),
			]
		);
	}

	/**
	 * @dataProvider provideIsArchive
	 */
	public function testTryCreatingRevisionRecord( $isArchive, $row ) {
		$pager = $this->getPagerForTryCreatingRevisionRecord( $isArchive );
		$pager->tryCreatingRevisionRecord( $row );
	}

	/**
	 * @dataProvider provideIsArchive
	 */
	public function testCreateRevisionRecord( $isArchive, $row ) {
		$pager = $this->getPagerForTryCreatingRevisionRecord( $isArchive );
		$pager->createRevisionRecord( $row );
	}

	public function provideIsArchive() {
		return [
			'Get revisions from the revision table' => [ false, [ 'rev_id' => 6789 ] ],
			'Get revisions from the archive table' => [ true, [ 'ar_rev_id' => 9876 ] ],
		];
	}

	/**
	 * Test the pager in 'archive' mode. This involves making a new class, since no
	 * concrete subclass in MediaWiki core currently uses this mode.
	 *
	 * In the future, SpecialDeletedContributions could use a subclass of ContributionsPager
	 * instead of DeletedContribsPager. In that case, this test can be moved to the tests
	 * for that class.
	 */
	public function testFormatRow() {
		// Set up data
		$user = $this->getTestUser()->getUser();
		$this->editPage(
			'Test page for deletion', 'Test Content', 'test', NS_MAIN, $user
		);
		$title = Title::newFromText( 'Test page for deletion' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->deletePage( $page );

		$services = $this->getServiceContainer();
		$context = new RequestContext();
		$context->setLanguage( 'qqx' );

		// Define a pager in 'archive' mode.
		$pager = new class(
			$services->getLinkRenderer(),
			$services->getLinkBatchFactory(),
			$services->getHookContainer(),
			$services->getRevisionStore(),
			$services->getNamespaceInfo(),
			$services->getCommentFormatter(),
			$services->getUserFactory(),
			$context,
			[
				'isArchive' => true,
				'target' => $user->getName()
			],
			$this->createMock( UserIdentity::class ),
		) extends ContributionsPager {
			protected string $revisionIdField = 'ar_rev_id';
			protected string $revisionParentIdField = 'ar_parent_id';
			protected string $revisionTimestampField = 'ar_timestamp';
			protected string $revisionLengthField = 'ar_len';
			protected string $revisionDeletedField = 'ar_deleted';
			protected string $revisionMinorField = 'ar_minor_edit';
			protected string $userNameField = 'ar_user_text';
			protected string $pageNamespaceField = 'ar_namespace';
			protected string $pageTitleField = 'ar_title';

			protected function getRevisionQuery() {
				$revQuery = $this->revisionStore->newArchiveSelectQueryBuilder( $this->getDatabase() )
					->joinComment()
					->where( [ 'actor_name' => $this->target ] )
					->andWhere( $this->getNamespaceCond() )
					->getQueryInfo( 'joins' );
				return [
					'tables' => $revQuery['tables'],
					'fields' => $revQuery['fields'],
					'conds' => [],
					'options' => [],
					'join_conds' => $revQuery['joins'],
				];
			}

			public function getIndexField() {
				return 'ar_timestamp';
			}
		};

		// Perform assertions
		$this->assertSame( 1, $pager->getNumRows() );

		$html = $pager->getBody();
		$this->assertStringContainsString( 'deletionlog', $html );
		$this->assertStringContainsString( 'undeleteviewlink', $html );
	}

}
