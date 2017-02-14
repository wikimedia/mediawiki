<?php
/**
 * Test class for SpecialRecentchanges class
 *
 * @group Database
 *
 * @covers SpecialRecentChanges
 */
class SpecialRecentchangesTest extends AbstractChangesListSpecialPageTestCase {
	protected function setUp() {
		parent::setUp();

		# setup the rc object
		$this->changesListSpecialPage = new SpecialRecentchanges;
	}

	public function provideParseParameters() {
		return [
			[ 'limit=123', [ 'limit' => '123' ] ],

			[ '234' => [ 'limit' => '234' ] ],

			[ 'days=3', [ 'days' => '4' /* XXX */ ] ],

			[ 'namespace=5', [ 'namespace' => 5 ] ],

			[ 'tagfilter=foo', [ 'tagfilter' => 'foo' ] ],
		];
	}
}