<?php

class NewUsersLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideNewUsersLogDatabaseRows() {
		return array(
			// Only old logs
			array(
				array(
					'type' => 'newusers',
					'action' => 'newusers',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'New user',
					'namespace' => NS_USER,
					'title' => 'New user',
					'params' => array(),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-newusers-newusers',
					'paramCount' => 3,
				),
			),
		);
	}

	/**
	 * @dataProvider provideNewUsersLogDatabaseRows
	 */
	public function testNewUsersLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideCreateLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'newusers',
					'action' => 'create',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'New user',
					'namespace' => NS_USER,
					'title' => 'New user',
					'params' => array(
						'4::userid' => '1',
					),
				),
				array(
					'key' => 'logentry-newusers-create',
					'paramCount' => 4,
				),
			),
		);
	}

	/**
	 * @dataProvider provideCreateLogDatabaseRows
	 */
	public function testCreateLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideCreate2LogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'newusers',
					'action' => 'create2',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'New user',
					'namespace' => NS_USER,
					'title' => 'New user',
					'params' => array(
						'4::userid' => '1',
					),
				),
				array(
					'key' => 'logentry-newusers-create2',
					'paramCount' => 4,
				),
			),
		);
	}

	/**
	 * @dataProvider provideCreate2LogDatabaseRows
	 */
	public function testCreate2LogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideBymailLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'newusers',
					'action' => 'bymail',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'New user',
					'params' => array(
						'4::userid' => '1',
					),
				),
				array(
					'key' => 'logentry-newusers-bymail',
					'paramCount' => 4,
				),
			),
		);
	}

	/**
	 * @dataProvider provideBymailLogDatabaseRows
	 */
	public function testBymailLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideAutocreateLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'newusers',
					'action' => 'autocreate',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'New user',
					'namespace' => NS_USER,
					'title' => 'New user',
					'params' => array(
						'4::userid' => '1',
					),
				),
				array(
					'key' => 'logentry-newusers-autocreate',
					'paramCount' => 4,
				),
			),
		);
	}

	/**
	 * @dataProvider provideAutocreateLogDatabaseRows
	 */
	public function testAutocreateLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
