<?php

/**
 * @group Database
 */
class NewUsersLogFormatterTest extends LogFormatterTestCase {

	protected function setUp() {
		parent::setUp();

		// Register LogHandler, see $wgNewUserLog in Setup.php
		$this->mergeMwGlobalArrayValue( 'wgLogActionsHandlers', array(
			'newusers/newusers' => 'NewUsersLogFormatter',
			'newusers/create' => 'NewUsersLogFormatter',
			'newusers/create2' => 'NewUsersLogFormatter',
			'newusers/byemail' => 'NewUsersLogFormatter',
			'newusers/autocreate' => 'NewUsersLogFormatter',
		) );
	}

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
					'text' => 'User account New user was created',
					'api' => array(),
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
						'4::userid' => 1,
					),
				),
				array(
					'text' => 'User account New user was created',
					'api' => array(
						'userid' => 1,
					),
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
					'user_text' => 'User',
					'namespace' => NS_USER,
					'title' => 'UTSysop',
					'params' => array(
						'4::userid' => 1,
					),
				),
				array(
					'text' => 'User account UTSysop was created by User',
					'api' => array(
						'userid' => 1,
					),
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
	public static function provideByemailLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'newusers',
					'action' => 'byemail',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'UTSysop',
					'params' => array(
						'4::userid' => 1,
					),
				),
				array(
					'text' => 'User account UTSysop was created by Sysop and password was sent by email',
					'api' => array(
						'userid' => 1,
					),
				),
			),
		);
	}

	/**
	 * @dataProvider provideByemailLogDatabaseRows
	 */
	public function testByemailLogDatabaseRows( $row, $extra ) {
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
						'4::userid' => 1,
					),
				),
				array(
					'text' => 'User account New user was created automatically',
					'api' => array(
						'userid' => 1,
					),
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
