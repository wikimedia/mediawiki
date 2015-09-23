<?php

class MoveLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideMoveLogDatabaseRows() {
		return array(
			// Current format - with redirect
			array(
				array(
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment with redirect',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'4::target' => 'NewPage',
						'5::noredir' => '0',
					),
				),
				array(
					'text' => 'User moved page OldPage to NewPage',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					),
				),
			),

			// Current format - without redirect
			array(
				array(
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'4::target' => 'NewPage',
						'5::noredir' => '1',
					),
				),
				array(
					'text' => 'User moved page OldPage to NewPage without leaving a redirect',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => true,
					),
				),
			),

			// legacy format - with redirect
			array(
				array(
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'NewPage',
						'',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					),
				),
			),

			// legacy format - without redirect
			array(
				array(
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'NewPage',
						'1',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage without leaving a redirect',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => true,
					),
				),
			),

			// old format without flag for redirect suppression
			array(
				array(
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'NewPage',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					),
				),
			),
		);
	}

	/**
	 * @dataProvider provideMoveLogDatabaseRows
	 */
	public function testMoveLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideMoveRedirLogDatabaseRows() {
		return array(
			// Current format - with redirect
			array(
				array(
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment with redirect',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'4::target' => 'NewPage',
						'5::noredir' => '0',
					),
				),
				array(
					'text' => 'User moved page OldPage to NewPage over redirect',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					),
				),
			),

			// Current format - without redirect
			array(
				array(
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'4::target' => 'NewPage',
						'5::noredir' => '1',
					),
				),
				array(
					'text' => 'User moved page OldPage to NewPage over a redirect without leaving a redirect',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => true,
					),
				),
			),

			// legacy format - with redirect
			array(
				array(
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'NewPage',
						'',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage over redirect',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					),
				),
			),

			// legacy format - without redirect
			array(
				array(
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'NewPage',
						'1',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage over a redirect without leaving a redirect',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => true,
					),
				),
			),

			// old format without flag for redirect suppression
			array(
				array(
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => array(
						'NewPage',
					),
				),
				array(
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage over redirect',
					'api' => array(
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					),
				),
			),
		);
	}

	/**
	 * @dataProvider provideMoveRedirLogDatabaseRows
	 */
	public function testMoveRedirLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
