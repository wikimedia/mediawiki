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
						'4::description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'5:bool:cascade' => false,
						'details' => array(
							array(
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							),
							array(
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							),
						),
					),
				),
				array(
					'text' => 'User protected ProtectPage [Edit=Allow only administrators] (indefinite) [Move=Allow only administrators] (indefinite)',
					'api' => array(
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => false,
						'details' => array(
							array(
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							),
							array(
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							),
						),
					),
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
						'4::description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'5:bool:cascade' => true,
						'details' => array(
							array(
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => true,
							),
							array(
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							),
						),
					),
				),
				array(
					'text' => 'User protected ProtectPage [Edit=Allow only administrators] (indefinite) [Move=Allow only administrators] (indefinite) [cascading]',
					'api' => array(
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => true,
						'details' => array(
							array(
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => true,
							),
							array(
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							),
						),
					),
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
						'[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User protected ProtectPage [edit=sysop] (indefinite)[move=sysop] (indefinite)',
					'api' => array(
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => false,
					),
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
						'[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User protected ProtectPage [edit=sysop] (indefinite)[move=sysop] (indefinite) [cascading]',
					'api' => array(
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => true,
					),
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
						'4::description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'5:bool:cascade' => false,
						'details' => array(
							array(
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							),
							array(
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							),
						),
					),
				),
				array(
					'text' => 'User changed protection level for ProtectPage [Edit=Allow only administrators] (indefinite) [Move=Allow only administrators] (indefinite)',
					'api' => array(
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => false,
						'details' => array(
							array(
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							),
							array(
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							),
						),
					),
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
						'4::description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'5:bool:cascade' => true,
						'details' => array(
							array(
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => true,
							),
							array(
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							),
						),
					),
				),
				array(
					'text' => 'User changed protection level for ProtectPage [Edit=Allow only administrators] (indefinite) [Move=Allow only administrators] (indefinite) [cascading]',
					'api' => array(
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => true,
						'details' => array(
							array(
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => true,
							),
							array(
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							),
						),
					),
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
						'[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User changed protection level for ProtectPage [edit=sysop] (indefinite)[move=sysop] (indefinite)',
					'api' => array(
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => false,
					),
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
						'[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User changed protection level for ProtectPage [edit=sysop] (indefinite)[move=sysop] (indefinite) [cascading]',
					'api' => array(
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => true,
					),
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
					'text' => 'User removed protection from ProtectPage',
					'api' => array(),
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
					'text' => 'User moved protection settings from OldPage to NewPage',
					'api' => array(
						'oldtitle_ns' => 0,
						'oldtitle_title' => 'OldPage',
					),
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
					'text' => 'User moved protection settings from OldPage to NewPage',
					'api' => array(
						'oldtitle_ns' => 0,
						'oldtitle_title' => 'OldPage',
					),
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
