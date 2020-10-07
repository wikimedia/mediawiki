<?php

/**
 * @covers NewUsersLogFormatter
 * @group Database
 */
class NewUsersLogFormatterTest extends LogFormatterTestCase {

	protected function setUp() : void {
		parent::setUp();

		// Register LogHandler, see $wgNewUserLog in Setup.php
		$this->mergeMwGlobalArrayValue( 'wgLogActionsHandlers', [
			'newusers/newusers' => NewUsersLogFormatter::class,
			'newusers/create' => NewUsersLogFormatter::class,
			'newusers/create2' => NewUsersLogFormatter::class,
			'newusers/byemail' => NewUsersLogFormatter::class,
			'newusers/autocreate' => NewUsersLogFormatter::class,
		] );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideNewUsersLogDatabaseRows() {
		return [
			// Only old logs
			[
				[
					'type' => 'newusers',
					'action' => 'newusers',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'New user',
					'namespace' => NS_USER,
					'title' => 'New user',
					'params' => [],
				],
				[
					'legacy' => true,
					'text' => 'User account New user was created',
					'api' => [],
				],
			],
		];
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
		return [
			// Current format
			[
				[
					'type' => 'newusers',
					'action' => 'create',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'New user',
					'namespace' => NS_USER,
					'title' => 'New user',
					'params' => [
						'4::userid' => 1,
					],
				],
				[
					'text' => 'User account New user was created',
					'api' => [
						'userid' => 1,
					],
				],
			],
		];
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
		return [
			// Current format
			[
				[
					'type' => 'newusers',
					'action' => 'create2',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'User',
					'namespace' => NS_USER,
					'title' => 'UTSysop'
				],
				[
					'text' => 'User account UTSysop was created by User'
				],
			],
		];
	}

	/**
	 * @dataProvider provideCreate2LogDatabaseRows
	 */
	public function testCreate2LogDatabaseRows( $row, $extra ) {
		// Make UTSysop user and use its user_id (sequence does not reset to 1 for postgres)
		$user = static::getTestSysop()->getUser();
		$row['params']['4::userid'] = $user->getId();
		$extra['api']['userid'] = $user->getId();
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideByemailLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'newusers',
					'action' => 'byemail',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'UTSysop'
				],
				[
					'text' => 'User account UTSysop was created by Sysop and password was sent by email'
				],
			],
		];
	}

	/**
	 * @dataProvider provideByemailLogDatabaseRows
	 */
	public function testByemailLogDatabaseRows( $row, $extra ) {
		// Make UTSysop user and use its user_id (sequence does not reset to 1 for postgres)
		$user = static::getTestSysop()->getUser();
		$row['params']['4::userid'] = $user->getId();
		$extra['api']['userid'] = $user->getId();
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideAutocreateLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'newusers',
					'action' => 'autocreate',
					'comment' => 'newusers comment',
					'user' => 0,
					'user_text' => 'New user',
					'namespace' => NS_USER,
					'title' => 'New user',
					'params' => [
						'4::userid' => 1,
					],
				],
				[
					'text' => 'User account New user was created automatically',
					'api' => [
						'userid' => 1,
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideAutocreateLogDatabaseRows
	 */
	public function testAutocreateLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
