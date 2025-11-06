<?php
namespace MediaWiki\Tests\Logging;

use MediaWiki\Title\TitleValue;

/**
 * @covers \MediaWiki\Logging\BlockLogFormatter
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
						'5::duration' => 'infinity',
						'6::flags' => 'anononly',
					],
				],
				[
					'text' => 'Sysop blocked Logtestuser with an expiration time of indefinite'
						. ' (anonymous users only)',
					'api' => [
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
					],
					'preload' => [ new TitleValue( NS_USER_TALK, 'Logtestuser' ) ],
				],
			],

			// Old log format with one of the 4 values for 'infinity'
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
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
					],
					'preload' => [ new TitleValue( NS_USER_TALK, 'Logtestuser' ) ],
				],
			],

			// With blank page title (T224811)
			[
				[
					'type' => 'block',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => '',
					'params' => [],
				],
				[
					'text' => 'Sysop blocked (no username available) '
						. 'with an expiration time of indefinite',
					'api' => [
						'duration' => 'infinity',
						'flags' => [],
						'duration-l10n' => 'infinite',
					],
					'preload' => [],
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
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
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
						'duration' => 'infinity',
						'flags' => [],
						'duration-l10n' => 'infinite',
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
						'duration' => 'infinity',
						'flags' => [],
						'duration-l10n' => 'infinite',
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
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
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
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
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
						'duration' => 'infinity',
						'flags' => [],
						'duration-l10n' => 'infinite',
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
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
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
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
					],
				],
			],
		];
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideSuppressBlockLogDatabaseRowsNonPrivileged() {
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
					'text' => '(username removed) (log details removed)',
					'api' => [
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
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
					'text' => '(username removed) (log details removed)',
					'api' => [
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideSuppressBlockLogDatabaseRowsNonPrivileged
	 */
	public function testSuppressBlockLogDatabaseRowsNonPrivileged( $row, $extra ) {
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
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
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
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideSuppressBlockLogDatabaseRows
	 * @dataProvider provideSuppressReblockLogDatabaseRows
	 */
	public function testSuppressBlockLogDatabaseRows( $row, $extra ) {
		$this->setGroupPermissions(
			[
				'oversight' => [
					'viewsuppressed' => true,
					'suppressionlog' => true,
				],
			]
		);
		$this->doTestLogFormatter( $row, $extra, [ 'oversight' ] );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideSuppressReblockLogDatabaseRowsNonPrivileged() {
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
					'text' => '(username removed) (log details removed)',
					'api' => [
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
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
					'text' => '(username removed) (log details removed)',
					'api' => [
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'duration-l10n' => 'infinite',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideSuppressReblockLogDatabaseRowsNonPrivileged
	 */
	public function testSuppressReblockLogDatabaseRowsNonPrivileged( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	public static function providePartialBlockLogDatabaseRows() {
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
					'text' => 'Sysop blocked Logtestuser from the pages User:Test1 and Main Page'
						. ' with an expiration time of indefinite (anonymous users only)',
					'api' => [
						'duration' => 'infinity',
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
						'duration-l10n' => 'infinite',
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
					'text' => 'Sysop blocked Logtestuser from the namespace User'
						. ' with an expiration time of indefinite (anonymous users only)',
					'api' => [
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'restrictions' => [
							'namespaces' => [ NS_USER ],
						],
						'sitewide' => false,
						'duration-l10n' => 'infinite',
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
					'text' => 'Sysop blocked Logtestuser from the page Main Page and the'
						. ' namespaces User and (Main) with an expiration time of indefinite'
						. ' (anonymous users only)',
					'api' => [
						'duration' => 'infinity',
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
						'duration-l10n' => 'infinite',
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
						'duration' => 'infinity',
						'flags' => [ 'anononly' ],
						'sitewide' => false,
						'duration-l10n' => 'infinite',
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

	public static function provideMultiblocksDatabaseRows() {
		return [
			// Sitewide multiblock with expiry
			[
				[
					'type' => 'block',
					'action' => 'block',
					'comment' => 'multiblock',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Target',
					'timestamp' => '20240101000000',
					'params' => [
						'5::duration' => '24 hours',
						'6::flags' => '',
						'sitewide' => true,
						'finalTargetCount' => 2,
					]
				],
				[
					'text' => 'Sysop added a block for Target with an expiration time of 1 day',
					'api' => [
						'duration' => '24 hours',
						'flags' => [],
						'finalTargetCount' => 2,
						'sitewide' => true,
						'expiry' => '2024-01-02T00:00:00Z',
						'duration-l10n' => '1 day',
					]
				]
			],
		];
	}

	/**
	 * @dataProvider provideMultiblocksDatabaseRows
	 */
	public function testMultiblocksDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
