<?php

class DeleteLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideDeleteLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'delete',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(),
				),
				array(
					'text' => 'User deleted page Page',
					'api' => array(),
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'delete',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(),
				),
				array(
					'legacy' => true,
					'text' => 'User deleted page Page',
					'api' => array(),
				),
			),
		);
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
		return array(
			// Current format
			array(
				array(
					'type' => 'delete',
					'action' => 'restore',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(),
				),
				array(
					'text' => 'User restored page Page',
					'api' => array(),
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'delete',
					'action' => 'restore',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(),
				),
				array(
					'legacy' => true,
					'text' => 'User restored page Page',
					'api' => array(),
				),
			),
		);
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
		return array(
			// Current format
			array(
				array(
					'type' => 'delete',
					'action' => 'revision',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'4::type' => 'archive',
						'5::ids' => array( '1', '3', '4' ),
						'6::ofield' => '1',
						'7::nfield' => '2',
					),
				),
				array(
					'text' => 'User changed visibility of 3 revisions on page Page: edit summary '
						. 'hidden and content unhidden',
					'api' => array(
						'type' => 'archive',
						'ids' => array( '1', '3', '4' ),
						'old' => array(
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						),
						'new' => array(
							'bitmask' => 2,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => false,
						),
					),
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'delete',
					'action' => 'revision',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'archive',
						'1,3,4',
						'ofield=1',
						'nfield=2',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User changed visibility of 3 revisions on page Page: edit summary '
						. 'hidden and content unhidden',
					'api' => array(
						'type' => 'archive',
						'ids' => array( '1', '3', '4' ),
						'old' => array(
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						),
						'new' => array(
							'bitmask' => 2,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => false,
						),
					),
				),
			),
		);
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
		return array(
			// Current format
			array(
				array(
					'type' => 'delete',
					'action' => 'event',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'4::ids' => array( '1', '3', '4' ),
						'5::ofield' => '1',
						'6::nfield' => '2',
					),
				),
				array(
					'text' => 'User changed visibility of 3 log events on Page: edit summary hidden '
						. 'and content unhidden',
					'api' => array(
						'type' => 'logging',
						'ids' => array( '1', '3', '4' ),
						'old' => array(
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						),
						'new' => array(
							'bitmask' => 2,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => false,
						),
					),
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'delete',
					'action' => 'event',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'1,3,4',
						'ofield=1',
						'nfield=2',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User changed visibility of 3 log events on Page: edit summary hidden '
						. 'and content unhidden',
					'api' => array(
						'type' => 'logging',
						'ids' => array( '1', '3', '4' ),
						'old' => array(
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						),
						'new' => array(
							'bitmask' => 2,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => false,
						),
					),
				),
			),
		);
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
		return array(
			// Current format
			array(
				array(
					'type' => 'suppress',
					'action' => 'revision',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'4::type' => 'archive',
						'5::ids' => array( '1', '3', '4' ),
						'6::ofield' => '1',
						'7::nfield' => '10',
					),
				),
				array(
					'text' => 'User secretly changed visibility of 3 revisions on page Page: edit '
						. 'summary hidden, content unhidden and applied restrictions to administrators',
					'api' => array(
						'type' => 'archive',
						'ids' => array( '1', '3', '4' ),
						'old' => array(
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						),
						'new' => array(
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						),
					),
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'suppress',
					'action' => 'revision',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'archive',
						'1,3,4',
						'ofield=1',
						'nfield=10',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User secretly changed visibility of 3 revisions on page Page: edit '
						. 'summary hidden, content unhidden and applied restrictions to administrators',
					'api' => array(
						'type' => 'archive',
						'ids' => array( '1', '3', '4' ),
						'old' => array(
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						),
						'new' => array(
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						),
					),
				),
			),
		);
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
		return array(
			// Current format
			array(
				array(
					'type' => 'suppress',
					'action' => 'event',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'4::ids' => array( '1', '3', '4' ),
						'5::ofield' => '1',
						'6::nfield' => '10',
					),
				),
				array(
					'text' => 'User secretly changed visibility of 3 log events on Page: edit '
						. 'summary hidden, content unhidden and applied restrictions to administrators',
					'api' => array(
						'type' => 'logging',
						'ids' => array( '1', '3', '4' ),
						'old' => array(
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						),
						'new' => array(
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						),
					),
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'suppress',
					'action' => 'event',
					'comment' => 'Suppress comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'1,3,4',
						'ofield=1',
						'nfield=10',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User secretly changed visibility of 3 log events on Page: edit '
						. 'summary hidden, content unhidden and applied restrictions to administrators',
					'api' => array(
						'type' => 'logging',
						'ids' => array( '1', '3', '4' ),
						'old' => array(
							'bitmask' => 1,
							'content' => true,
							'comment' => false,
							'user' => false,
							'restricted' => false,
						),
						'new' => array(
							'bitmask' => 10,
							'content' => false,
							'comment' => true,
							'user' => false,
							'restricted' => true,
						),
					),
				),
			),
		);
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
		return array(
			// Current format
			array(
				array(
					'type' => 'suppress',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(),
				),
				array(
					'text' => 'User suppressed page Page',
					'api' => array(),
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'suppress',
					'action' => 'delete',
					'comment' => 'delete comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(),
				),
				array(
					'legacy' => true,
					'text' => 'User suppressed page Page',
					'api' => array(),
				),
			),
		);
	}

	/**
	 * @dataProvider provideSuppressDeleteLogDatabaseRows
	 */
	public function testSuppressDeleteLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
