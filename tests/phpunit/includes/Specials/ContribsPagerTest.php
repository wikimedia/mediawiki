<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Pager\ContribsPager;
use MediaWiki\Pager\IndexPager;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @covers \MediaWiki\Pager\ContribsPager
 * @covers \MediaWiki\Pager\ContributionsPager
 */
class ContribsPagerTest extends MediaWikiIntegrationTestCase {
	use TempUserTestTrait;

	/** @var ContribsPager */
	private $pager;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var HookContainer */
	private $hookContainer;

	/** @var IConnectionProvider */
	private $dbProvider;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var CommentFormatter */
	private $commentFormatter;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->revisionStore = $services->getRevisionStore();
		$this->linkBatchFactory = $services->getLinkBatchFactory();
		$this->hookContainer = $services->getHookContainer();
		$this->dbProvider = $services->getConnectionProvider();
		$this->namespaceInfo = $services->getNamespaceInfo();
		$this->commentFormatter = $services->getCommentFormatter();
		$this->pager = $this->getContribsPager( [
			'start' => '2017-01-01',
			'end' => '2017-02-02',
		] );
	}

	private function getContribsPager( array $options, ?UserIdentity $targetUser = null ) {
		return new ContribsPager(
			new RequestContext(),
			$options,
			$this->linkRenderer,
			$this->linkBatchFactory,
			$this->hookContainer,
			$this->dbProvider,
			$this->revisionStore,
			$this->namespaceInfo,
			$targetUser,
			$this->commentFormatter
		);
	}

	/**
	 * Tests enabling/disabling ContribsPager::reallyDoQuery hook via the revisionsOnly option to restrict
	 * extensions are able to insert their own revisions
	 */
	public function testRevisionsOnlyOption() {
		$this->setTemporaryHook( 'ContribsPager::reallyDoQuery', static function ( &$data ) {
			$fakeRow = (object)[ 'rev_timestamp' => '20200717192356' ];
			$fakeRowWrapper = new FakeResultWrapper( [ $fakeRow ] );
			$data[] = $fakeRowWrapper;
		} );

		$allContribsPager = $this->getContribsPager( [] );
		$allContribsResults = $allContribsPager->reallyDoQuery( '', 2, IndexPager::QUERY_DESCENDING );
		$this->assertSame( 1, $allContribsResults->numRows() );

		$revOnlyPager = $this->getContribsPager( [ 'revisionsOnly' => true ] );
		$revOnlyResults = $revOnlyPager->reallyDoQuery( '', 2, IndexPager::QUERY_DESCENDING );
		$this->assertSame( 0, $revOnlyResults->numRows() );
	}

	/**
	 * @dataProvider dateFilterOptionProcessingProvider
	 * @param array $inputOpts Input options
	 * @param array $expectedOpts Expected options
	 */
	public function testDateFilterOptionProcessing( array $inputOpts, array $expectedOpts ) {
		$this->assertArraySubmapSame(
			$expectedOpts,
			ContribsPager::processDateFilter( $inputOpts ),
			"Matching date filter options"
		);
	}

	public static function dateFilterOptionProcessingProvider() {
		return [
			[
				[
					'start' => '2016-05-01',
					'end' => '2016-06-01',
					'year' => null,
					'month' => null
				],
				[
					'start' => '2016-05-01',
					'end' => '2016-06-01'
				]
			],
			[
				[
					'start' => '2016-05-01',
					'end' => '2016-06-01',
					'year' => '',
					'month' => ''
				],
				[
					'start' => '2016-05-01',
					'end' => '2016-06-01'
				]
			],
			[
				[
					'start' => '2016-05-01',
					'end' => '2016-06-01',
					'year' => '2012',
					'month' => '5'
				],
				[
					'start' => '',
					'end' => '2012-05-31'
				]
			],
			[
				[
					'start' => '',
					'end' => '',
					'year' => '2012',
					'month' => '5'
				],
				[
					'start' => '',
					'end' => '2012-05-31'
				]
			],
			[
				[
					'start' => '',
					'end' => '',
					'year' => '2012',
					'month' => ''
				],
				[
					'start' => '',
					'end' => '2012-12-31'
				]
			],
		];
	}

	public function testUniqueSortOrderWithoutIpChanges() {
		$pager = $this->getContribsPager( [
			'start' => '',
			'end' => '',
		] );

		/** @var ContribsPager $pager */
		$pager = TestingAccessWrapper::newFromObject( $pager );
		$queryInfo = $pager->buildQueryInfo( '', 1, false );

		$this->assertNotContains( 'ip_changes', $queryInfo[0] );
		$this->assertArrayNotHasKey( 'ip_changes', $queryInfo[5] );
		$this->assertContains( 'rev_timestamp', $queryInfo[1] );
		$this->assertContains( 'rev_id', $queryInfo[1] );
		$this->assertSame( [ 'rev_timestamp DESC', 'rev_id DESC' ], $queryInfo[4]['ORDER BY'] );
	}

	public function testUniqueSortOrderOnIpChanges() {
		$pager = $this->getContribsPager( [
			'target' => '116.17.184.5/32',
			'start' => '',
			'end' => '',
		] );

		/** @var ContribsPager $pager */
		$pager = TestingAccessWrapper::newFromObject( $pager );
		$queryInfo = $pager->buildQueryInfo( '', 1, false );

		$this->assertContains( 'ip_changes', $queryInfo[0] );
		$this->assertArrayHasKey( 'revision', $queryInfo[5] );
		$this->assertSame( [ 'ipc_rev_timestamp DESC', 'ipc_rev_id DESC' ], $queryInfo[4]['ORDER BY'] );
	}

	public function testCreateRevision() {
		$page = PageIdentityValue::localIdentity( 13, NS_MAIN, __METHOD__ );

		$pager = $this->getContribsPager( [
			'target' => '116.17.184.5/32',
			'start' => '',
			'end' => '',
		] );

		$invalidObject = new class() {
			/** @var string */
			public $rev_id;
		};
		$this->assertNull( $pager->tryCreatingRevisionRecord( $invalidObject, $page ) );

		$invalidRow = (object)[
			'foo' => 'bar'
		];

		$this->assertNull( $pager->tryCreatingRevisionRecord( $invalidRow, $page ) );

		$validRow = (object)[
			'rev_id' => '2',
			'rev_page' => $page->getId(),
			'rev_text_id' => '47',
			'rev_timestamp' => '20180528192356',
			'rev_minor_edit' => '0',
			'rev_deleted' => '0',
			'rev_len' => '700',
			'rev_parent_id' => '0',
			'rev_sha1' => 'deadbeef',
			'rev_comment_text' => 'whatever',
			'rev_comment_data' => null,
			'rev_comment_cid' => null,
			'rev_user' => '1',
			'rev_user_text' => 'Editor',
			'rev_actor' => '11',
			'rev_content_format' => null,
			'rev_content_model' => null,
		];

		$this->assertNotNull( $pager->tryCreatingRevisionRecord( $validRow, $page ) );
	}

	/**
	 * Flow uses ContribsPager::reallyDoQuery hook to provide something other then
	 * stdClass as a row, and then manually formats its own row in ContributionsLineEnding.
	 * Emulate this behaviour and check that it works.
	 */
	public function testContribProvidedByHook() {
		$this->setTemporaryHook( 'ContribsPager::reallyDoQuery', static function ( &$data ) {
			$data = [ [ new class() {
				public $rev_timestamp = 12345;
				public $testing = 'TESTING';
			} ] ];
		} );
		$this->setTemporaryHook( 'ContributionsLineEnding', function ( $pager, &$ret, $row ) {
			$this->assertSame( 'TESTING', $row->testing );
			$ret .= 'FROM_HOOK!';
		} );
		$pager = $this->getContribsPager( [] );
		$this->assertStringContainsString( 'FROM_HOOK!', $pager->getBody() );
	}

	public static function provideEmptyResultIntegration() {
		$cases = [
			[ 'target' => '127.0.0.1' ],
			[ 'target' => '127.0.0.1/24' ],
			[ 'testUser' => true ],
			[ 'target' => '127.0.0.1', 'namespace' => 0 ],
			[ 'target' => '127.0.0.1', 'namespace' => 0, 'nsInvert' => true ],
			[ 'target' => '127.0.0.1', 'namespace' => 0, 'associated' => true ],
			[ 'target' => '127.0.0.1', 'tagfilter' => 'tag' ],
			[ 'target' => '127.0.0.1', 'topOnly' => true ],
			[ 'target' => '127.0.0.1', 'newOnly' => true ],
			[ 'target' => '127.0.0.1', 'hideMinor' => true ],
			[ 'target' => '127.0.0.1', 'revisionsOnly' => true ],
			[ 'target' => '127.0.0.1', 'deletedOnly' => true ],
			[ 'target' => '127.0.0.1', 'start' => '20010115000000' ],
			[ 'target' => '127.0.0.1', 'end' => '20210101000000' ],
			[ 'target' => '127.0.0.1', 'start' => '20010115000000', 'end' => '20210101000000' ],
		];
		foreach ( $cases as $case ) {
			yield [ $case ];
		}
	}

	/**
	 * This DB integration test confirms that the query is valid for various
	 * filter options, by running the query on an empty DB.
	 *
	 * @dataProvider provideEmptyResultIntegration
	 */
	public function testEmptyResultIntegration( $options ) {
		if ( !empty( $options['testUser'] ) ) {
			$targetUser = new UserIdentityValue( 1, 'User' );
		} else {
			$targetUser = $this->getServiceContainer()->getUserFactory()
				->newFromName( $options['target'] );
		}
		$pager = $this->getContribsPager( $options, $targetUser );
		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 0, $pager->getNumRows() );
	}

	/**
	 * DB integration test for an IP range target with a few edits.
	 */
	public function testPopulatedIntegration() {
		$this->disableAutoCreateTempUser();
		$user = new SimpleAuthority( new UserIdentityValue( 0, '127.0.0.1' ), [] );
		$title = Title::makeTitle( NS_MAIN, 'ContribsPagerTest' );
		$this->editPage( $title, '', '', NS_MAIN, $user );
		$this->editPage( $title, 'Test content.', '', NS_MAIN, $user );
		$pager = $this->getContribsPager( [ 'target' => '127.0.0.1/16' ] );
		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 2, $pager->getNumRows() );
	}

	/**
	 * DB integration test for a reader with permissions to delete and rollback.
	 */
	public function testPopulatedIntegrationWithPermissions() {
		$this->setGroupPermissions( [ '*' => [
			'deletedhistory' => true,
			'deleterevision' => true,
			'rollback' => true,
		] ] );
		$sysop = $this->getTestsysop()->getUser();
		$user = $this->getTestUser()->getUser();
		$title = Title::makeTitle( NS_MAIN, 'ContribsPagerTest' );

		// Edit from a different user so we show rollback links
		$this->editPage( $title, '', '', NS_MAIN, $sysop );
		$this->editPage( $title, 'Test content.', '', NS_MAIN, $user );

		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [
				'rev_deleted' => RevisionRecord::DELETED_USER,
				// Make a couple of alterations to ensure these paths are covered
				'rev_minor_edit' => 1,
				'rev_parent_id' => null,
			] )
			->where( [
				'rev_actor' => $user->getActorId()
			] )
			->execute();

		$pager = $this->getContribsPager( [], $user );
		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 1, $pager->getNumRows() );
	}

	/** @dataProvider provideHasAppliedFilters */
	public function testHasAppliedFilters( $options, $expectedReturnValue ) {
		$pager = $this->getContribsPager( $options );
		$this->assertSame( $expectedReturnValue, $pager->hasAppliedFilters() );
	}

	public static function provideHasAppliedFilters() {
		return [
			'Filters for namespace' => [ [ 'target' => '127.0.0.1', 'namespace' => NS_MAIN ], true ],
			'Filters for tagfilter' => [ [ 'target' => '127.0.0.1', 'tagfilter' => 'tag' ], true ],
			'Filters for multiple tagfilter' => [
				[ 'target' => '127.0.0.1', 'tagfilter' => [ 'tag', 'tag2' ] ], true
			],
			'Filters for topOnly' => [ [ 'target' => '127.0.0.1', 'topOnly' => true ], true ],
			'Filters for newOnly' => [ [ 'target' => '127.0.0.1', 'newOnly' => true ], true ],
			'Filters for hideMinor' => [ [ 'target' => '127.0.0.1', 'hideMinor' => true ], true ],
			'Filters for deletedOnly' => [ [ 'target' => '127.0.0.1', 'deletedOnly' => true ], true ],
			'Filters for start' => [ [ 'target' => '127.0.0.1', 'start' => '2025-04-05' ], true ],
			'Filters for end' => [ [ 'target' => '127.0.0.1', 'end' => '2025-05-05' ], true ],
			'Filters for start and end' => [
				[ 'target' => '127.0.0.1', 'start' => '2025-04-05', 'end' => '2025-05-05' ], true
			],
			'No filters (except for target)' => [ [ 'target' => '1.2.3.4' ], false ],
		];
	}
}
