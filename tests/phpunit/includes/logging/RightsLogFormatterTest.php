<?php

class RightsLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideRightsLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [
						'4::oldgroups' => [],
						'5::newgroups' => [ 'sysop', 'bureaucrat' ],
					],
				],
				[
					'text' => 'Sysop changed group membership for User from (none) to '
						. 'administrator and bureaucrat',
					'api' => [
						'oldgroups' => [],
						'newgroups' => [ 'sysop', 'bureaucrat' ],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [
						'',
						'sysop, bureaucrat',
					],
				],
				[
					'legacy' => true,
					'text' => 'Sysop changed group membership for User from (none) to '
						. 'administrator and bureaucrat',
					'api' => [
						'oldgroups' => [],
						'newgroups' => [ 'sysop', 'bureaucrat' ],
					],
				],
			],

			// Really old entry
			[
				[
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [],
				],
				[
					'legacy' => true,
					'text' => 'Sysop changed group membership for User',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideRightsLogDatabaseRows
	 */
	public function testRightsLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideAutopromoteLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'rights',
					'action' => 'autopromote',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Sysop',
					'params' => [
						'4::oldgroups' => [ 'sysop' ],
						'5::newgroups' => [ 'sysop', 'bureaucrat' ],
					],
				],
				[
					'text' => 'Sysop was automatically promoted from administrator to '
						. 'administrator and bureaucrat',
					'api' => [
						'oldgroups' => [ 'sysop' ],
						'newgroups' => [ 'sysop', 'bureaucrat' ],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'rights',
					'action' => 'autopromote',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Sysop',
					'params' => [
						'sysop',
						'sysop, bureaucrat',
					],
				],
				[
					'legacy' => true,
					'text' => 'Sysop was automatically promoted from administrator to '
						. 'administrator and bureaucrat',
					'api' => [
						'oldgroups' => [ 'sysop' ],
						'newgroups' => [ 'sysop', 'bureaucrat' ],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideAutopromoteLogDatabaseRows
	 */
	public function testAutopromoteLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
