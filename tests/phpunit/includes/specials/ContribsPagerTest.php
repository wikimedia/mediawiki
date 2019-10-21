<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class ContribsPagerTest extends MediaWikiTestCase {
	/** @var ContribsPager */
	private $pager;

	/** @var LinkRenderer */
	private $linkRenderer;

	function setUp() {
		$this->linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		$context = new RequestContext();
		$this->pager = new ContribsPager( $context, [
			'start' => '2017-01-01',
			'end' => '2017-02-02',
		], $this->linkRenderer );

		parent::setUp();
	}

	/**
	 * @covers ContribsPager::processDateFilter
	 * @dataProvider dateFilterOptionProcessingProvider
	 * @param array $inputOpts Input options
	 * @param array $expectedOpts Expected options
	 */
	public function testDateFilterOptionProcessing( array $inputOpts, array $expectedOpts ) {
		$this->assertArraySubset( $expectedOpts, ContribsPager::processDateFilter( $inputOpts ) );
	}

	public static function dateFilterOptionProcessingProvider() {
		return [
			[ [ 'start' => '2016-05-01',
				'end' => '2016-06-01',
				'year' => null,
				'month' => null ],
			  [ 'start' => '2016-05-01',
				'end' => '2016-06-01' ] ],
			[ [ 'start' => '2016-05-01',
				'end' => '2016-06-01',
				'year' => '',
				'month' => '' ],
			  [ 'start' => '2016-05-01',
				'end' => '2016-06-01' ] ],
			[ [ 'start' => '2016-05-01',
				'end' => '2016-06-01',
				'year' => '2012',
				'month' => '5' ],
			  [ 'start' => '',
				'end' => '2012-05-31' ] ],
			[ [ 'start' => '',
				'end' => '',
				'year' => '2012',
				'month' => '5' ],
			  [ 'start' => '',
				'end' => '2012-05-31' ] ],
			[ [ 'start' => '',
				'end' => '',
				'year' => '2012',
				'month' => '' ],
			  [ 'start' => '',
				'end' => '2012-12-31' ] ],
		];
	}

	/**
	 * @covers ContribsPager::isQueryableRange
	 * @dataProvider provideQueryableRanges
	 */
	public function testQueryableRanges( $ipRange ) {
		$this->setMwGlobals( [
			'wgRangeContributionsCIDRLimit' => [
				'IPv4' => 16,
				'IPv6' => 32,
			],
		] );

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
		$this->setMwGlobals( [
			'wgRangeContributionsCIDRLimit' => [
				'IPv4' => 16,
				'IPv6' => 32,
			],
		] );

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
	 */
	public function testUniqueSortOrderWithoutIpChanges() {
		$pager = new ContribsPager( new RequestContext(), [
			'start' => '',
			'end' => '',
		], $this->linkRenderer );

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
	 */
	public function testUniqueSortOrderOnIpChanges() {
		$pager = new ContribsPager( new RequestContext(), [
			'target' => '116.17.184.5/32',
			'start' => '',
			'end' => '',
		], $this->linkRenderer );

		/** @var ContribsPager $pager */
		$pager = TestingAccessWrapper::newFromObject( $pager );
		$queryInfo = $pager->buildQueryInfo( '', 1, false );

		$this->assertContains( 'ip_changes', $queryInfo[0] );
		$this->assertArrayHasKey( 'ip_changes', $queryInfo[5] );
		$this->assertSame( [ 'ipc_rev_timestamp DESC', 'ipc_rev_id DESC' ], $queryInfo[4]['ORDER BY'] );
	}

}
