<?php

class MergeLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideMergeLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'merge',
					'action' => 'merge',
					'comment' => 'Merge comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'4::dest' => 'NewPage',
						'5::mergepoint' => '20140804160710',
					],
				],
				[
					'text' => 'User merged OldPage into NewPage (revisions up to 16:07, 4 August 2014)',
					'api' => [
						'dest_ns' => 0,
						'dest_title' => 'NewPage',
						'mergepoint' => '2014-08-04T16:07:10Z',
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'merge',
					'action' => 'merge',
					'comment' => 'merge comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'NewPage',
						'20140804160710',
					],
				],
				[
					'legacy' => true,
					'text' => 'User merged OldPage into NewPage (revisions up to 16:07, 4 August 2014)',
					'api' => [
						'dest_ns' => 0,
						'dest_title' => 'NewPage',
						'mergepoint' => '2014-08-04T16:07:10Z',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideMergeLogDatabaseRows
	 */
	public function testMergeLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
