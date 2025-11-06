<?php
namespace MediaWiki\Tests\Logging;

/**
 * @covers \MediaWiki\Logging\MoveLogFormatter
 */
class MoveLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideMoveLogDatabaseRows() {
		return [
			// Current format - with redirect
			[
				[
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment with redirect',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'4::target' => 'NewPage',
						'5::noredir' => '0',
					],
				],
				[
					'text' => 'User moved page OldPage to NewPage',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					],
				],
			],

			// Current format - without redirect
			[
				[
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'4::target' => 'NewPage',
						'5::noredir' => '1',
					],
				],
				[
					'text' => 'User moved page OldPage to NewPage without leaving a redirect',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => true,
					],
				],
			],

			// legacy format - with redirect
			[
				[
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'NewPage',
						'',
					],
				],
				[
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					],
				],
			],

			// legacy format - without redirect
			[
				[
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'NewPage',
						'1',
					],
				],
				[
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage without leaving a redirect',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => true,
					],
				],
			],

			// old format without flag for redirect suppression
			[
				[
					'type' => 'move',
					'action' => 'move',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'NewPage',
					],
				],
				[
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					],
				],
			],

			// row with invalid title (T370396)
			[
				[
					'type' => 'move',
					'action' => 'move',
					'comment' => 'comment',
					'namespace' => NS_TALK,
					'title' => 'OldPage',
					'params' => [
						'4::target' => 'Talk:Help:NewPage',
						'5::noredir' => '0',
					],
				],
				[
					'text' => 'User moved page Talk:OldPage to Invalid title',
					'api' => [
						'target_ns' => -1,
						'target_title' => 'Special:Badtitle/Talk:Help:NewPage',
						'suppressredirect' => false,
					],
					'preload' => [ /* empty, do not try to preload the bad title */ ],
				],
			],
		];
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
		return [
			// Current format - with redirect
			[
				[
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment with redirect',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'4::target' => 'NewPage',
						'5::noredir' => '0',
					],
				],
				[
					'text' => 'User moved page OldPage to NewPage over redirect',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					],
				],
			],

			// Current format - without redirect
			[
				[
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'4::target' => 'NewPage',
						'5::noredir' => '1',
					],
				],
				[
					'text' => 'User moved page OldPage to NewPage over a redirect without leaving a redirect',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => true,
					],
				],
			],

			// legacy format - with redirect
			[
				[
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'NewPage',
						'',
					],
				],
				[
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage over redirect',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					],
				],
			],

			// legacy format - without redirect
			[
				[
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'NewPage',
						'1',
					],
				],
				[
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage over a redirect without leaving a redirect',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => true,
					],
				],
			],

			// old format without flag for redirect suppression
			[
				[
					'type' => 'move',
					'action' => 'move_redir',
					'comment' => 'move comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'NewPage',
					],
				],
				[
					'legacy' => true,
					'text' => 'User moved page OldPage to NewPage over redirect',
					'api' => [
						'target_ns' => 0,
						'target_title' => 'NewPage',
						'suppressredirect' => false,
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideMoveRedirLogDatabaseRows
	 */
	public function testMoveRedirLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
