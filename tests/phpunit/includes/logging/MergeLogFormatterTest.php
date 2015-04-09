<?php

class MergeLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideMergeLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'merge',
					'action' => 'merge',
					'comment' => 'Merge comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'4::dest' => 'NewPage',
						'5::mergepoint' => '20140804160710',
					),
				),
				array(
					'text' => 'User merged OldPage into NewPage (revisions up to 16:07, 4 August 2014)',
					'api' => array( 0 => 'NewPage', 1 => '20140804160710' ),
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'merge',
					'action' => 'merge',
					'comment' => 'merge comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'NewPage',
						'20140804160710',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User merged OldPage into NewPage (revisions up to 16:07, 4 August 2014)',
					'api' => array( 0 => 'NewPage', 1 => '20140804160710' ),
				),
			),
		);
	}

	/**
	 * @dataProvider provideMergeLogDatabaseRows
	 */
	public function testMergeLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
