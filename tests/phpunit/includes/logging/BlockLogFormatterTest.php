<?php

class BlockLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideBlockLogDatabaseRows() {
		return [
			// Current log format
			[
				[
					'type' => 'block',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'5::duration' => 'infinite',
						'6::flags' => 'anononly',
					],
				],
				[
					'text' => 'Sysop blocked Logtestuser with an expiration time of indefinite'
						. ' (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
					],
				],
			],

			// Old legacy log
			[
				[
					'type' => 'block',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'infinite',
						'anononly',
					],
				],
				[
					'legacy' => true,
					'text' => 'Sysop blocked Logtestuser with an expiration time of indefinite'
						. ' (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
					],
				],
			],

			// Old legacy log without flag
			[
				[
					'type' => 'block',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'infinite',
					],
				],
				[
					'legacy' => true,
					'text' => 'Sysop blocked Logtestuser with an expiration time of indefinite',
					'api' => [
						'duration' => 'infinite',
						'flags' => [],
					],
				],
			],

			// Very old legacy log without duration
			[
				[
					'type' => 'block',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [],
				],
				[
					'legacy' => true,
					'text' => 'Sysop blocked Logtestuser with an expiration time of indefinite',
					'api' => [
						'duration' => 'infinite',
						'flags' => [],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideBlockLogDatabaseRows
	 */
	public function testBlockLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideReblockLogDatabaseRows() {
		return [
			// Current log format
			[
				[
					'type' => 'block',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'5::duration' => 'infinite',
						'6::flags' => 'anononly',
					],
				],
				[
					'text' => 'Sysop changed block settings for Logtestuser with an expiration time of'
						. ' indefinite (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
					],
				],
			],

			// Old log
			[
				[
					'type' => 'block',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'infinite',
						'anononly',
					],
				],
				[
					'legacy' => true,
					'text' => 'Sysop changed block settings for Logtestuser with an expiration time of'
						. ' indefinite (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
					],
				],
			],

			// Older log without flag
			[
				[
					'type' => 'block',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'infinite',
					]
				],
				[
					'legacy' => true,
					'text' => 'Sysop changed block settings for Logtestuser with an expiration time of indefinite',
					'api' => [
						'duration' => 'infinite',
						'flags' => [],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideReblockLogDatabaseRows
	 */
	public function testReblockLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideUnblockLogDatabaseRows() {
		return [
			// Current log format
			[
				[
					'type' => 'block',
					'action' => 'unblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [],
				],
				[
					'text' => 'Sysop unblocked Logtestuser',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideUnblockLogDatabaseRows
	 */
	public function testUnblockLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideSuppressBlockLogDatabaseRows() {
		return [
			// Current log format
			[
				[
					'type' => 'suppress',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'5::duration' => 'infinite',
						'6::flags' => 'anononly',
					],
				],
				[
					'text' => 'Sysop blocked Logtestuser with an expiration time of indefinite'
						. ' (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
					],
				],
			],

			// legacy log
			[
				[
					'type' => 'suppress',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'infinite',
						'anononly',
					],
				],
				[
					'legacy' => true,
					'text' => 'Sysop blocked Logtestuser with an expiration time of indefinite'
						. ' (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideSuppressBlockLogDatabaseRows
	 */
	public function testSuppressBlockLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideSuppressReblockLogDatabaseRows() {
		return [
			// Current log format
			[
				[
					'type' => 'suppress',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'5::duration' => 'infinite',
						'6::flags' => 'anononly',
					],
				],
				[
					'text' => 'Sysop changed block settings for Logtestuser with an expiration time of'
						. ' indefinite (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'suppress',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => [
						'infinite',
						'anononly',
					],
				],
				[
					'legacy' => true,
					'text' => 'Sysop changed block settings for Logtestuser with an expiration time of'
						. ' indefinite (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideSuppressReblockLogDatabaseRows
	 */
	public function testSuppressReblockLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
