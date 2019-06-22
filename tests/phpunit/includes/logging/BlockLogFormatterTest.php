<?php

/**
 * @covers BlockLogFormatter
 */
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

	public function providePartialBlockLogDatabaseRows() {
		return [
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
						'7::restrictions' => [ 'pages' => [ 'User:Test1', 'Main Page' ] ],
						'sitewide' => false,
					],
				],
				[
					'text' => 'Sysop blocked Logtestuser from editing the pages User:Test1 and Main Page'
						. ' with an expiration time of indefinite (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
						'restrictions' => [
							'pages' => [
								[
									'page_ns' => 2,
									'page_title' => 'User:Test1',
								], [
									'page_ns' => 0,
									'page_title' => 'Main Page',
								],
							],
						],
						'sitewide' => false,
					],
				],
			],
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
						'7::restrictions' => [
							'namespaces' => [ NS_USER ],
						],
						'sitewide' => false,
					],
				],
				[
					'text' => 'Sysop blocked Logtestuser from editing the namespace User'
						. ' with an expiration time of indefinite (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
						'restrictions' => [
							'namespaces' => [ NS_USER ],
						],
						'sitewide' => false,
					],
				],
			],
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
						'7::restrictions' => [
							'pages' => [ 'Main Page' ],
							'namespaces' => [ NS_USER, NS_MAIN ],
						],
						'sitewide' => false,
					],
				],
				[
					'text' => 'Sysop blocked Logtestuser from editing the page Main Page and the'
						. ' namespaces User and (Main) with an expiration time of indefinite'
						. ' (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
						'restrictions' => [
							'pages' => [
								[
									'page_ns' => 0,
									'page_title' => 'Main Page',
								],
							],
							'namespaces' => [ NS_USER, NS_MAIN ],
						],
						'sitewide' => false,
					],
				],
			],
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
						'sitewide' => false,
					],
				],
				[
					'text' => 'Sysop blocked Logtestuser from specified non-editing actions'
						. ' with an expiration time of indefinite (anonymous users only)',
					'api' => [
						'duration' => 'infinite',
						'flags' => [ 'anononly' ],
						'sitewide' => false,
					],
				],
			],
		];
	}

	/**
	 * @dataProvider providePartialBlockLogDatabaseRows
	 */
	public function testPartialBlockLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
