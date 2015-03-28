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
					'key' => 'logentry-block-block',
					'paramCount' => 6,
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
					'key' => 'logentry-block-block',
					'paramCount' => 6,
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
					'key' => 'logentry-block-block',
					'paramCount' => 6,
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
					'key' => 'logentry-block-block',
					'paramCount' => 6,
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
					'key' => 'logentry-block-reblock',
					'paramCount' => 6,
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
					'key' => 'logentry-block-reblock',
					'paramCount' => 6,
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
					'key' => 'logentry-block-reblock',
					'paramCount' => 6,
				),
			),
		);
	}

	/**
	 * @dataProvider provideReblockLogDatabaseRows
	 */
	public function testRelockLogDatabaseRows( $row, $extra ) {
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
					'key' => 'logentry-block-unblock',
					'paramCount' => 4,
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
					'key' => 'logentry-suppress-block',
					'paramCount' => 6,
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
					'key' => 'logentry-suppress-block',
					'paramCount' => 6,
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
					'key' => 'logentry-suppress-reblock',
					'paramCount' => 6,
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
					'key' => 'logentry-suppress-reblock',
					'paramCount' => 6,
				),
			),
		);
	}

	/**
	 * @dataProvider provideSuppressReblockLogDatabaseRows
	 */
	public function testSuppressRelockLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
