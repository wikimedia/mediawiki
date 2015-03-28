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
					'key' => 'logentry-merge-merge',
					'paramCount' => 5,
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
					'key' => 'logentry-merge-merge',
					'paramCount' => 5,
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
