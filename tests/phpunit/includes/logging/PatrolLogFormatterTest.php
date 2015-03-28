<?php

class PatrolLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function providePatrolLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'patrol',
					'action' => 'patrol',
					'comment' => 'patrol comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'4::curid' => 2,
						'5::previd' => 1,
						'6::auto' => 0,
					),
				),
				array(
					'key' => 'logentry-patrol-patrol',
					'paramCount' => 6,
				),
			),

			// Current format - autopatrol
			array(
				array(
					'type' => 'patrol',
					'action' => 'patrol',
					'comment' => 'patrol comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'4::curid' => 2,
						'5::previd' => 1,
						'6::auto' => 1,
					),
				),
				array(
					'key' => 'logentry-patrol-patrol-auto',
					'paramCount' => 6,
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'patrol',
					'action' => 'patrol',
					'comment' => 'patrol comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'2',
						'1',
						'0',
					),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-patrol-patrol',
					'paramCount' => 6,
				),
			),

			// Legacy format - autopatrol
			array(
				array(
					'type' => 'patrol',
					'action' => 'patrol',
					'comment' => 'patrol comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'2',
						'1',
						'1',
					),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-patrol-patrol-auto',
					'paramCount' => 6,
				),
			),
		);
	}

	/**
	 * @dataProvider providePatrolLogDatabaseRows
	 */
	public function testPatrolLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
