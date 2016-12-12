<?php

/**
 * @group Database
 */
class SpecialContributionsTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider dateFilterOptionProcessingProvider
	 * @param array $inputOpts Input options
	 * @param array $expectedOpts Expected options
	 */
	public function testDateFilterOptionProcessing( $inputOpts, $expectedOpts ) {
		$this->assertArraySubset( $expectedOpts, SpecialContributions::processDateFilter( $inputOpts ) );
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
}
