<?php

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
					'params' => [],
				],
				[
					'text' => 'User restored page Page',
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
					'text' => 'User restored page Page',
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
		];
	}

	/**
	 * @dataProvider provideSuppressRevisionLogDatabaseRows
	 */
	public function testSuppressRevisionLogDatabaseRows( $row, $extra ) {
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
		];
	}

	/**
	 * @dataProvider provideSuppressEventLogDatabaseRows
	 */
	public function testSuppressEventLogDatabaseRows( $row, $extra ) {
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
	 * @dataProvider provideSuppressDeleteLogDatabaseRows
	 */
	public function testSuppressDeleteLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
