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
					'key' => 'logentry-move-move',
					'paramCount' => 4,
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
					'key' => 'logentry-move-move-noredirect',
					'paramCount' => 4,
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
					'key' => 'logentry-move-move',
					'paramCount' => 4,
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
					'key' => 'logentry-move-move-noredirect',
					'paramCount' => 4,
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
					'key' => 'logentry-move-move',
					'paramCount' => 4,
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
					'key' => 'logentry-move-move_redir',
					'paramCount' => 4,
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
					'key' => 'logentry-move-move_redir-noredirect',
					'paramCount' => 4,
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
					'key' => 'logentry-move-move_redir',
					'paramCount' => 4,
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
					'key' => 'logentry-move-move_redir-noredirect',
					'paramCount' => 4,
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
					'key' => 'logentry-move-move_redir',
					'paramCount' => 4,
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
