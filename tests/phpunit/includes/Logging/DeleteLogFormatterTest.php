<?php
namespace MediaWiki\Tests\Logging;

/**
 * @covers \MediaWiki\Logging\DeleteLogFormatter
 */
class DeleteLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideDeleteLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'delete',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [],
				],
				[
					'text' => 'User deleted page Page',
					'api' => [],
				],
			],

			// Legacy format
			[
				[
					'type' => 'delete',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [],
				],
				[
					'legacy' => true,
					'text' => 'User deleted page Page',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideDeleteLogDatabaseRows
	 */
	public function testDeleteLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideRestoreLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'delete',
					'action' => 'restore',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						':assoc:count' => [
							'revisions' => 2,
							'files' => 1,
						],
					],
				],
				[
					'text' => 'User undeleted page Page (2 revisions and 1 file)',
					'api' => [
						'count' => [
							'revisions' => 2,
							'files' => 1,
						],
					],
				],
			],

			// Legacy format without counts
			[
				[
					'type' => 'delete',
					'action' => 'restore',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [],
				],
				[
					'text' => 'User undeleted page Page',
					'api' => [],
				],
			],

			// Legacy format
			[
				[
					'type' => 'delete',
					'action' => 'restore',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [],
				],
				[
					'legacy' => true,
					'text' => 'User undeleted page Page',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideRestoreLogDatabaseRows
	 */
	public function testRestoreLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideRevisionLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'delete',
					'action' => 'revision',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'4::type' => 'archive',
						'5::ids' => [ '1', '3', '4' ],
						'6::ofield' => '1',
						'7::nfield' => '2',
					],
				],
				[
					'text' => 'User changed visibility of 3 revisions on page Page: edit summary '
						. 'hidden and content unhidden',
					'api' => [
						'type' => 'archive',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 2,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => false,
						],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'delete',
					'action' => 'revision',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'archive',
						'1,3,4',
						'ofield=1',
						'nfield=2',
					],
				],
				[
					'legacy' => true,
					'text' => 'User changed visibility of 3 revisions on page Page: edit summary '
						. 'hidden and content unhidden',
					'api' => [
						'type' => 'archive',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 2,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => false,
						],
					],
				],
			],
			// Legacy format pre-T20361, the changes part of the comment
			[
				[
					'type' => 'delete',
					'action' => 'revision',
					'comment' => 'edit summary hidden and content unhidden: delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'archive',
						'1,3,4',
					],
				],
				[
					'legacy' => true,
					'text' => 'User changed visibility of revisions on page Page',
					'api' => [
						'type' => 'archive',
						'ids' => [ '1', '3', '4' ],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideRevisionLogDatabaseRows
	 */
	public function testRevisionLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideEventLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'delete',
					'action' => 'event',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'4::ids' => [ '1', '3', '4' ],
						'5::ofield' => '1',
						'6::nfield' => '2',
					],
				],
				[
					'text' => 'User changed visibility of 3 log events on Page: edit summary hidden '
						. 'and content unhidden',
					'api' => [
						'type' => 'logging',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 2,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => false,
						],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'delete',
					'action' => 'event',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'1,3,4',
						'ofield=1',
						'nfield=2',
					],
				],
				[
					'legacy' => true,
					'text' => 'User changed visibility of 3 log events on Page: edit summary hidden '
						. 'and content unhidden',
					'api' => [
						'type' => 'logging',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 2,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => false,
						],
					],
				],
			],

			// Legacy format pre-T20361, the changes part of the comment
			[
				[
					'type' => 'delete',
					'action' => 'event',
					'comment' => 'edit summary hidden and content unhidden: delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'1,3,4',
					],
				],
				[
					'legacy' => true,
					'text' => 'User changed visibility of log events on Page',
					'api' => [
						'type' => 'logging',
						'ids' => [ '1', '3', '4' ],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideEventLogDatabaseRows
	 */
	public function testEventLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideSuppressRevisionLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'suppress',
					'action' => 'revision',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'4::type' => 'archive',
						'5::ids' => [ '1', '3', '4' ],
						'6::ofield' => '1',
						'7::nfield' => '10',
					],
				],
				[
					'text' => 'User secretly changed visibility of 3 revisions on page Page: edit '
						. 'summary hidden, content unhidden and applied restrictions to administrators',
					'api' => [
						'type' => 'archive',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'suppress',
					'action' => 'revision',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'archive',
						'1,3,4',
						'ofield=1',
						'nfield=10',
					],
				],
				[
					'legacy' => true,
					'text' => 'User secretly changed visibility of 3 revisions on page Page: edit '
						. 'summary hidden, content unhidden and applied restrictions to administrators',
					'api' => [
						'type' => 'archive',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						],
					],
				],
			],

			// Legacy format pre-T20361, the changes part of the comment
			[
				[
					'type' => 'suppress',
					'action' => 'revision',
					'comment' => 'edit summary hidden, content unhidden and applied restrictions to administrators: '
						. 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'archive',
						'1,3,4',
					],
				],
				[
					'legacy' => true,
					'text' => 'User secretly changed visibility of revisions on page Page',
					'api' => [
						'type' => 'archive',
						'ids' => [ '1', '3', '4' ],
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
	public static function provideSuppressRevisionLogDatabaseRowsNonPrivileged() {
		return [
			// Current format
			[
				[
					'type' => 'suppress',
					'action' => 'revision',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'4::type' => 'archive',
						'5::ids' => [ '1', '3', '4' ],
						'6::ofield' => '1',
						'7::nfield' => '10',
					],
				],
				[
					'text' => '(username removed) (log details removed)',
					'api' => [
						'type' => 'archive',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'suppress',
					'action' => 'revision',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'archive',
						'1,3,4',
						'ofield=1',
						'nfield=10',
					],
				],
				[
					'legacy' => true,
					'text' => '(username removed) (log details removed)',
					'api' => [
						'type' => 'archive',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						],
					],
				],
			],

			// Legacy format pre-T20361, the changes part of the comment
			[
				[
					'type' => 'suppress',
					'action' => 'revision',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'archive',
						'1,3,4',
					],
				],
				[
					'legacy' => true,
					'text' => '(username removed) (log details removed)',
					'api' => [
						'type' => 'archive',
						'ids' => [ '1', '3', '4' ],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideSuppressRevisionLogDatabaseRowsNonPrivileged
	 */
	public function testSuppressRevisionLogDatabaseRowsNonPrivileged( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideSuppressEventLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'suppress',
					'action' => 'event',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'4::ids' => [ '1', '3', '4' ],
						'5::ofield' => '1',
						'6::nfield' => '10',
					],
				],
				[
					'text' => 'User secretly changed visibility of 3 log events on Page: edit '
						. 'summary hidden, content unhidden and applied restrictions to administrators',
					'api' => [
						'type' => 'logging',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						],
					],
				],
			],

			// Legacy formats
			[
				[
					'type' => 'suppress',
					'action' => 'event',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'1,3,4',
						'ofield=1',
						'nfield=10',
					],
				],
				[
					'legacy' => true,
					'text' => 'User secretly changed visibility of 3 log events on Page: edit '
						. 'summary hidden, content unhidden and applied restrictions to administrators',
					'api' => [
						'type' => 'logging',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						],
					],
				],
			],

			// Legacy format pre-T20361, the changes part of the comment
			[
				[
					'type' => 'delete',
					'action' => 'revision',
					'comment' => 'Old rows might lack ofield/nfield (T224815)',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'oldid',
						'1234',
					],
				],
				[
					'legacy' => true,
					'text' => 'User changed visibility of revisions on page Page',
					'api' => [
						'type' => 'oldid',
						'ids' => [ '1234' ],
					],
				],
			]
		];
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideSuppressEventLogDatabaseRowsNonPrivileged() {
		return [
			// Current format
			[
				[
					'type' => 'suppress',
					'action' => 'event',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'4::ids' => [ '1', '3', '4' ],
						'5::ofield' => '1',
						'6::nfield' => '10',
					],
				],
				[
					'text' => '(username removed) (log details removed)',
					'api' => [
						'type' => 'logging',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'suppress',
					'action' => 'event',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'1,3,4',
						'ofield=1',
						'nfield=10',
					],
				],
				[
					'legacy' => true,
					'text' => '(username removed) (log details removed)',
					'api' => [
						'type' => 'logging',
						'ids' => [ '1', '3', '4' ],
						'old' => [
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						],
						'new' => [
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						],
					],
				],
			],

			// Legacy format pre-T20361, the changes part of the comment
			[
				[
					'type' => 'suppress',
					'action' => 'event',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'1,3,4',
					],
				],
				[
					'legacy' => true,
					'text' => '(username removed) (log details removed)',
					'api' => [
						'type' => 'logging',
						'ids' => [ '1', '3', '4' ],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideSuppressEventLogDatabaseRowsNonPrivileged
	 */
	public function testSuppressEventLogDatabaseRowsNonPrivileged( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideSuppressDeleteLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'suppress',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [],
				],
				[
					'text' => 'User suppressed page Page',
					'api' => [],
				],
			],

			// Legacy format
			[
				[
					'type' => 'suppress',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [],
				],
				[
					'legacy' => true,
					'text' => 'User suppressed page Page',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideSuppressRevisionLogDatabaseRows
	 * @dataProvider provideSuppressEventLogDatabaseRows
	 * @dataProvider provideSuppressDeleteLogDatabaseRows
	 */
	public function testSuppressLogDatabaseRows( $row, $extra ) {
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
	public static function provideSuppressDeleteLogDatabaseRowsNonPrivileged() {
		return [
			// Current format
			[
				[
					'type' => 'suppress',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [],
				],
				[
					'text' => '(username removed) (log details removed)',
					'api' => [],
				],
			],

			// Legacy format
			[
				[
					'type' => 'suppress',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [],
				],
				[
					'legacy' => true,
					'text' => '(username removed) (log details removed)',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideSuppressDeleteLogDatabaseRowsNonPrivileged
	 */
	public function testSuppressDeleteLogDatabaseRowsNonPrivileged( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
