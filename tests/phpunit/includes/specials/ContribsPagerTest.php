<?php

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class ContribsPagerTest extends MediaWikiIntegrationTestCase {
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

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var ActorMigration */
	private $actorMigration;

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
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->actorMigration = $services->getActorMigration();
		$this->namespaceInfo = $services->getNamespaceInfo();
		$this->commentFormatter = $services->getCommentFormatter();
		$this->pager = $this->getContribsPager( [
			'start' => '2017-01-01',
			'end' => '2017-02-02',
		] );
	}

	private function getContribsPager( array $options, UserIdentity $targetUser = null ) {
		return new ContribsPager(
			new RequestContext(),
			$options,
			$this->linkRenderer,
			$this->linkBatchFactory,
			$this->hookContainer,
			$this->loadBalancer,
			$this->actorMigration,
			$this->revisionStore,
			$this->namespaceInfo,
			$targetUser,
			$this->commentFormatter
		);
	}

	/**
	 * @covers ContribsPager::reallyDoQuery
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
	 * @covers ContribsPager::processDateFilter
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

	/**
	 * @covers ContribsPager::isQueryableRange
	 * @dataProvider provideQueryableRanges
	 */
	public function testQueryableRanges( $ipRange ) {
		$this->overrideConfigValue(
			MainConfigNames::RangeContributionsCIDRLimit,
			[
				'IPv4' => 16,
				'IPv6' => 32,
			]
		);

		$this->assertTrue(
			$this->pager->isQueryableRange( $ipRange ),
			"$ipRange is a queryable IP range"
		);
	}

	public function provideQueryableRanges() {
		return [
			[ '116.17.184.5/32' ],
			[ '0.17.184.5/16' ],
			[ '2000::/32' ],
			[ '2001:db8::/128' ],
		];
	}

	/**
	 * @covers ContribsPager::isQueryableRange
	 * @dataProvider provideUnqueryableRanges
	 */
	public function testUnqueryableRanges( $ipRange ) {
		$this->overrideConfigValue(
			MainConfigNames::RangeContributionsCIDRLimit,
			[
				'IPv4' => 16,
				'IPv6' => 32,
			]
		);

		$this->assertFalse(
			$this->pager->isQueryableRange( $ipRange ),
			"$ipRange is not a queryable IP range"
		);
	}

	public function provideUnqueryableRanges() {
		return [
			[ '116.17.184.5/33' ],
			[ '0.17.184.5/15' ],
			[ '2000::/31' ],
			[ '2001:db8::/9999' ],
		];
	}

	/**
	 * @covers \ContribsPager::getExtraSortFields
	 * @covers \ContribsPager::getIndexField
	 * @covers \ContribsPager::getQueryInfo
	 * @covers \ContribsPager::getTargetTable
	 */
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

	/**
	 * @covers \ContribsPager::getExtraSortFields
	 * @covers \ContribsPager::getIndexField
	 * @covers \ContribsPager::getQueryInfo
	 * @covers \ContribsPager::getTargetTable
	 */
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

	/**
	 * @covers \ContribsPager::tryCreatingRevisionRecord
	 */
	public function testCreateRevision() {
		$title = Title::makeTitle( NS_MAIN, __METHOD__ );

		$pager = $this->getContribsPager( [
			'target' => '116.17.184.5/32',
			'start' => '',
			'end' => '',
		] );

		$invalidObject = new class() {
			public $rev_id;
		};
		$this->assertNull( $pager->tryCreatingRevisionRecord( $invalidObject, $title ) );

		$invalidRow = (object)[
			'foo' => 'bar'
		];

		$this->assertNull( $pager->tryCreatingRevisionRecord( $invalidRow, $title ) );

		$validRow = (object)[
			'rev_id' => '2',
			'rev_page' => '2',
			'page_namespace' => $title->getNamespace(),
			'page_title' => $title->getDBkey(),
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

		$this->assertNotNull( $pager->tryCreatingRevisionRecord( $validRow, $title ) );
	}

	/**
	 * Flow uses ContribsPager::reallyDoQuery hook to provide something other then
	 * stdClass as a row, and then manually formats it's own row in ContributionsLineEnding.
	 * Emulate this behaviour and check that it works.
	 *
	 * @covers ContribsPager::formatRow
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

	public function provideEmptyResultIntegration() {
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
	 * @covers \ContribsPager::__construct
	 * @covers \ContribsPager::getQueryInfo
	 * @covers \ContribsPager::getDatabase
	 * @covers \ContribsPager::getIpRangeConds
	 * @covers \ContribsPager::getNamespaceCond
	 * @covers \ContribsPager::getIndexField
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
	}

	/**
	 * DB integration test with a row in the result set.
	 *
	 * @covers \ContribsPager::formatRow
	 * @covers \ContribsPager::doBatchLookups
	 */
	public function testPopulatedIntegration() {
		$this->tablesUsed[] = 'page';
		$user = $this->getTestUser()->getUser();
		$title = Title::makeTitle( NS_MAIN, 'ContribsPagerTest' );
		$this->editPage( $title, '', '', NS_MAIN, $user );
		$pager = $this->getContribsPager( [], $user );
		$this->assertIsString( $pager->getBody() );
	}

}
