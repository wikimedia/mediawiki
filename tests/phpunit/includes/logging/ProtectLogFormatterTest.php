<?php

class ProtectLogFormatterTest extends LogFormatterTestCase {

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
