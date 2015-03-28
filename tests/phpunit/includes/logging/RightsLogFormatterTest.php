<?php

class RightsLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideRightsLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_MAIN,
					'title' => 'User',
					'params' => array(
						'4::oldgroups' => array(),
						'5::newgroups' => array( 'sysop', 'bureaucrat' ),
					),
				),
				array(
					'key' => 'logentry-rights-rights',
					'paramCount' => 5,
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => array(
						'',
						'sysop, bureaucrat',
					),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-rights-rights',
					'paramCount' => 5,
				),
			),

			// Really old entry
			array(
				array(
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => array(),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-rights-rights-legacy',
					'paramCount' => 3,
				),
			),
		);
	}

	/**
	 * @dataProvider provideRightsLogDatabaseRows
	 */
	public function testRightsLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideAutopromoteLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'rights',
					'action' => 'autopromote',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_MAIN,
					'title' => 'User',
					'params' => array(
						'4::oldgroups' => array( 'sysop' ),
						'5::newgroups' => array( 'sysop', 'bureaucrat' ),
					),
				),
				array(
					'key' => 'logentry-rights-autopromote',
					'paramCount' => 5,
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'rights',
					'action' => 'autopromote',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => array(
						'sysop',
						'sysop, bureaucrat',
					),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-rights-autopromote',
					'paramCount' => 5,
				),
			),
		);
	}

	/**
	 * @dataProvider provideAutopromoteLogDatabaseRows
	 */
	public function testAutopromoteLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
