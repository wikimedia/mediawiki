<?php

/**
 * @group Database
 */
class ContribsPagerTest extends MediaWikiTestCase {
	/** @var ContribsPager */
	private $pager;

	function setUp() {
		$context = new RequestContext();
		$this->pager = new ContribsPager( $context, [
			'start' => '2017-01-01',
			'end' => '2017-02-02',
		] );

		parent::setUp();
	}

	/**
	 * @covers ContribsPager::processDateFilter
	 * @dataProvider dateFilterOptionProcessingProvider
	 * @param array $inputOpts Input options
	 * @param array $expectedOpts Expected options
	 */
	public function testDateFilterOptionProcessing( $inputOpts, $expectedOpts ) {
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
}
