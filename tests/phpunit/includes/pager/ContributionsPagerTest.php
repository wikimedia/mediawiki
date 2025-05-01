<?php

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Pager\ContributionsPager;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
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

	private static User $user;

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

	public static function provideIsArchive() {
		return [
			'Get revisions from the revision table' => [ false, [ 'rev_id' => 6789 ] ],
			'Get revisions from the archive table' => [ true, [ 'ar_rev_id' => 9876 ] ],
		];
	}

	private function getPager( $context, $overrideOptions, $overrideServices = [] ) {
		$services = $this->getServiceContainer();

		$options = array_merge( [
			'isArchive' => true,
			'target' => '1.2.3.4',
			// The topOnly filter should be ignored and not throw an error: T371495
			'topOnly' => true,
			'runHooks' => true,
		], $overrideOptions );

		// Define a pager in 'archive' mode.
		$pager = new class(
			$services->getLinkRenderer(),
			$services->getLinkBatchFactory(),
			$overrideServices['HookContainer'] ?? $services->getHookContainer(),
			$services->getRevisionStore(),
			$services->getNamespaceInfo(),
			$services->getCommentFormatter(),
			$services->getUserFactory(),
			$context,
			$options,
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

		return $pager;
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
		$context = new RequestContext();
		$context->setLanguage( 'qqx' );

		$pager = $this->getPager( $context, [ 'target' => self::$user->getName() ] );

		// Perform assertions
		$this->assertSame( 1, $pager->getNumRows() );

		$html = $pager->getBody();
		$this->assertStringContainsString( 'deletionlog', $html );
		$this->assertStringContainsString( 'undeleteviewlink', $html );

		// The performing user does not have the right to undelete
		$this->assertStringNotContainsString( 'mw-changeslist-date', $html );
	}

	public function testFormatRowDateLinks() {
		$context = new RequestContext();
		$context->setUser( $this->getTestSysop()->getUser() );

		$pager = $this->getPager( $context, [ 'target' => self::$user->getName() ] );

		$html = $pager->getBody();
		$this->assertStringContainsString( 'mw-changeslist-date', $html );
	}

	public function addDbDataOnce() {
		// Set up data
		self::$user = $this->getTestUser()->getUser();
		$this->editPage(
			'Test page for deletion', 'Test Content', 'test', NS_MAIN, self::$user
		);
		$title = Title::newFromText( 'Test page for deletion' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->deletePage( $page );
	}

	/**
	 * @dataProvider provideRunHooks
	 */
	public function testRunHooksPropertyIsChecked( $runHooks, $expectRunHooks ) {
		$hookContainer = $this->createMock( HookContainer::class );
		$hookContainer->expects( $expectRunHooks ? $this->atLeastOnce() : $this->never() )
			->method( 'run' );

		$pager = $this->getPager(
			new RequestContext(),
			[ 'runHooks' => $runHooks ],
			[ 'HookContainer' => $hookContainer ]
		);
		$pager->getBody();
	}

	public static function provideRunHooks() {
		return [
			'Do not run any hooks if runHooks is false' => [ false, false ],
			'Run hooks if runHooks is true' => [ true, true ],
		];
	}

}
