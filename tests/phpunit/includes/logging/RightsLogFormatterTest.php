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
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => array(
						'4::oldgroups' => array(),
						'5::newgroups' => array( 'sysop', 'bureaucrat' ),
					),
				),
				array(
					'text' => 'Sysop changed group membership for User:User from (none) to '
						. 'administrator and bureaucrat',
					'api' => array(
						'oldgroups' => array(),
						'newgroups' => array( 'sysop', 'bureaucrat' ),
					),
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
					'text' => 'Sysop changed group membership for User:User from (none) to '
						. 'administrator and bureaucrat',
					'api' => array(
						'oldgroups' => array(),
						'newgroups' => array( 'sysop', 'bureaucrat' ),
					),
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
					'text' => 'Sysop changed group membership for User:User',
					'api' => array(),
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
					'namespace' => NS_USER,
					'title' => 'Sysop',
					'params' => array(
						'4::oldgroups' => array( 'sysop' ),
						'5::newgroups' => array( 'sysop', 'bureaucrat' ),
					),
				),
				array(
					'text' => 'Sysop was automatically promoted from administrator to '
						. 'administrator and bureaucrat',
					'api' => array(
						'oldgroups' => array( 'sysop' ),
						'newgroups' => array( 'sysop', 'bureaucrat' ),
					),
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
					'title' => 'Sysop',
					'params' => array(
						'sysop',
						'sysop, bureaucrat',
					),
				),
				array(
					'legacy' => true,
					'text' => 'Sysop was automatically promoted from administrator to '
						. 'administrator and bureaucrat',
					'api' => array(
						'oldgroups' => array( 'sysop' ),
						'newgroups' => array( 'sysop', 'bureaucrat' ),
					),
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
