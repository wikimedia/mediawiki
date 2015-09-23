<?php

class BlockLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideBlockLogDatabaseRows() {
		return array(
			// Current log format
			array(
				array(
					'type' => 'block',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'5::duration' => 'infinite',
						'6::flags' => 'anononly',
					),
				),
				array(
					'text' => 'Sysop blocked Logtestuser with an expiry time of indefinite (anonymous users only)',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array( 'anononly' ),
					),
				),
			),

			// Old legacy log
			array(
				array(
					'type' => 'block',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'infinite',
						'anononly',
					),
				),
				array(
					'legacy' => true,
					'text' => 'Sysop blocked Logtestuser with an expiry time of indefinite (anonymous users only)',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array( 'anononly' ),
					),
				),
			),

			// Old legacy log without flag
			array(
				array(
					'type' => 'block',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'infinite',
					),
				),
				array(
					'legacy' => true,
					'text' => 'Sysop blocked Logtestuser with an expiry time of indefinite',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array(),
					),
				),
			),

			// Very old legacy log without duration
			array(
				array(
					'type' => 'block',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(),
				),
				array(
					'legacy' => true,
					'text' => 'Sysop blocked Logtestuser with an expiry time of indefinite',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array(),
					),
				),
			),
		);
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
		return array(
			// Current log format
			array(
				array(
					'type' => 'block',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'5::duration' => 'infinite',
						'6::flags' => 'anononly',
					),
				),
				array(
					'text' => 'Sysop changed block settings for Logtestuser with an expiry time of'
						. ' indefinite (anonymous users only)',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array( 'anononly' ),
					),
				),
			),

			// Old log
			array(
				array(
					'type' => 'block',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'infinite',
						'anononly',
					),
				),
				array(
					'legacy' => true,
					'text' => 'Sysop changed block settings for Logtestuser with an expiry time of'
						. ' indefinite (anonymous users only)',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array( 'anononly' ),
					),
				),
			),

			// Older log without flag
			array(
				array(
					'type' => 'block',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'infinite',
					)
				),
				array(
					'legacy' => true,
					'text' => 'Sysop changed block settings for Logtestuser with an expiry time of indefinite',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array(),
					),
				),
			),
		);
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
		return array(
			// Current log format
			array(
				array(
					'type' => 'block',
					'action' => 'unblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(),
				),
				array(
					'text' => 'Sysop unblocked Logtestuser',
					'api' => array(),
				),
			),
		);
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
		return array(
			// Current log format
			array(
				array(
					'type' => 'suppress',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'5::duration' => 'infinite',
						'6::flags' => 'anononly',
					),
				),
				array(
					'text' => 'Sysop blocked Logtestuser with an expiry time of indefinite (anonymous users only)',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array( 'anononly' ),
					),
				),
			),

			// legacy log
			array(
				array(
					'type' => 'suppress',
					'action' => 'block',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'infinite',
						'anononly',
					),
				),
				array(
					'legacy' => true,
					'text' => 'Sysop blocked Logtestuser with an expiry time of indefinite (anonymous users only)',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array( 'anononly' ),
					),
				),
			),
		);
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
		return array(
			// Current log format
			array(
				array(
					'type' => 'suppress',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'5::duration' => 'infinite',
						'6::flags' => 'anononly',
					),
				),
				array(
					'text' => 'Sysop changed block settings for Logtestuser with an expiry time of'
						. ' indefinite (anonymous users only)',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array( 'anononly' ),
					),
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'suppress',
					'action' => 'reblock',
					'comment' => 'Block comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Logtestuser',
					'params' => array(
						'infinite',
						'anononly',
					),
				),
				array(
					'legacy' => true,
					'text' => 'Sysop changed block settings for Logtestuser with an expiry time of'
						. ' indefinite (anonymous users only)',
					'api' => array(
						'duration' => 'infinite',
						'flags' => array( 'anononly' ),
					),
				),
			),
		);
	}

	/**
	 * @dataProvider provideSuppressReblockLogDatabaseRows
	 */
	public function testSuppressReblockLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
