<?php

class ProtectLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideProtectLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'protect',
					'action' => 'protect',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => array(
						'4::description' => 'Test description [edit=sysop:move=sysop]',
						'5::cascade' => '',
					),
				),
				array(
					'key' => 'logentry-protect-protect',
					'paramCount' => 4,
				),
			),

			// Current format with cascade
			array(
				array(
					'type' => 'protect',
					'action' => 'protect',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => array(
						'4::description' => 'Test description [edit=sysop:move=sysop]',
						'5::cascade' => 'cascade',
					),
				),
				array(
					'key' => 'logentry-protect-protect-cascade',
					'paramCount' => 4,
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'protect',
					'action' => 'protect',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => array(
						'Test description [edit=sysop:move=sysop]',
						'',
					),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-protect-protect',
					'paramCount' => 4,
				),
			),

			// Legacy format with cascade
			array(
				array(
					'type' => 'protect',
					'action' => 'protect',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => array(
						'Test description [edit=sysop:move=sysop]',
						'cascade',
					),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-protect-protect-cascade',
					'paramCount' => 4,
				),
			),
		);
	}

	/**
	 * @dataProvider provideProtectLogDatabaseRows
	 */
	public function testProtectLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideModifyLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'protect',
					'action' => 'modify',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => array(
						'4::description' => 'Test description [edit=sysop:move=sysop]',
						'5::cascade' => '',
					),
				),
				array(
					'key' => 'logentry-protect-modify',
					'paramCount' => 4,
				),
			),

			// Current format with cascade
			array(
				array(
					'type' => 'protect',
					'action' => 'modify',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => array(
						'4::description' => 'Test description [edit=sysop:move=sysop]',
						'5::cascade' => 'cascade',
					),
				),
				array(
					'key' => 'logentry-protect-modify-cascade',
					'paramCount' => 4,
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'protect',
					'action' => 'modify',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => array(
						'Test description [edit=sysop:move=sysop]',
						'',
					),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-protect-modify',
					'paramCount' => 4,
				),
			),

			// Legacy format with cascade
			array(
				array(
					'type' => 'protect',
					'action' => 'modify',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => array(
						'Test description [edit=sysop:move=sysop]',
						'cascade',
					),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-protect-modify-cascade',
					'paramCount' => 4,
				),
			),
		);
	}

	/**
	 * @dataProvider provideModifyLogDatabaseRows
	 */
	public function testModifyLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideUnprotectLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'protect',
					'action' => 'unprotect',
					'comment' => 'unprotect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => array(),
				),
				array(
					'key' => 'logentry-protect-unprotect',
					'paramCount' => 3,
				),
			),
		);
	}

	/**
	 * @dataProvider provideUnprotectLogDatabaseRows
	 */
	public function testUnprotectLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideMoveProtLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'protect',
					'action' => 'move_prot',
					'comment' => 'Move comment',
					'namespace' => NS_MAIN,
					'title' => 'NewPage',
					'params' => array(
						'4::oldtitle' => 'OldPage',
					),
				),
				array(
					'key' => 'logentry-protect-move_prot',
					'paramCount' => 4,
				),
			),

			// Legacy format
			array(
				array(
					'type' => 'protect',
					'action' => 'move_prot',
					'comment' => 'Move comment',
					'namespace' => NS_MAIN,
					'title' => 'NewPage',
					'params' => array(
						'OldPage',
					),
				),
				array(
					'legacy' => true,
					'key' => 'logentry-protect-move_prot',
					'paramCount' => 4,
				),
			),
		);
	}

	/**
	 * @dataProvider provideMoveProtLogDatabaseRows
	 */
	public function testMoveProtLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
