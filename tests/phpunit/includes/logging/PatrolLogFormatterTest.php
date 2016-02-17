<?php

class PatrolLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function providePatrolLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'patrol',
					'action' => 'patrol',
					'comment' => 'patrol comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'4::curid' => 2,
						'5::previd' => 1,
						'6::auto' => 0,
					],
				],
				[
					'text' => 'User marked revision 2 of page Page patrolled',
					'api' => [
						'curid' => 2,
						'previd' => 1,
						'auto' => false,
					],
				],
			],

			// Current format - autopatrol
			[
				[
					'type' => 'patrol',
					'action' => 'patrol',
					'comment' => 'patrol comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'4::curid' => 2,
						'5::previd' => 1,
						'6::auto' => 1,
					],
				],
				[
					'text' => 'User automatically marked revision 2 of page Page patrolled',
					'api' => [
						'curid' => 2,
						'previd' => 1,
						'auto' => true,
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'patrol',
					'action' => 'patrol',
					'comment' => 'patrol comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'2',
						'1',
						'0',
					],
				],
				[
					'legacy' => true,
					'text' => 'User marked revision 2 of page Page patrolled',
					'api' => [
						'curid' => 2,
						'previd' => 1,
						'auto' => false,
					],
				],
			],

			// Legacy format - autopatrol
			[
				[
					'type' => 'patrol',
					'action' => 'patrol',
					'comment' => 'patrol comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'2',
						'1',
						'1',
					],
				],
				[
					'legacy' => true,
					'text' => 'User automatically marked revision 2 of page Page patrolled',
					'api' => [
						'curid' => 2,
						'previd' => 1,
						'auto' => true,
					],
				],
			],
		];
	}

	/**
	 * @dataProvider providePatrolLogDatabaseRows
	 */
	public function testPatrolLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
