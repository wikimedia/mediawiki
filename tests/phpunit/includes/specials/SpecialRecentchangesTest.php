<?php

use Wikimedia\TestingAccessWrapper;

/**
 * Test class for SpecialRecentchanges class
 *
 * @group Database
 *
 * @covers SpecialRecentChanges
 */
class SpecialRecentchangesTest extends AbstractChangesListSpecialPageTestCase {
	protected function getPage() {
		return TestingAccessWrapper::newFromObject(
			new SpecialRecentchanges
		);
	}

	// Below providers should only be for features specific to
	// RecentChanges.  Otherwise, it should go in ChangesListSpecialPageTest

	public function provideParseParameters() {
		return [
			[ 'limit=123', [ 'limit' => '123' ] ],

			[ '234', [ 'limit' => '234' ] ],

			[ 'days=3', [ 'days' => '3' ] ],

			[ 'namespace=5', [ 'namespace' => 5 ] ],

			[ 'tagfilter=foo', [ 'tagfilter' => 'foo' ] ],
		];
	}

	public function validateOptionsProvider() {
		return [
			[
				// hidebots=1 is default for Special:RecentChanges
				[ 'hideanons' => 1, 'hideliu' => 1 ],
				true,
				[ 'hideliu' => 1 ],
			],
		];
	}
}
