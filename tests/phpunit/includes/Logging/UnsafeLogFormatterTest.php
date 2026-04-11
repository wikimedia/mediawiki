<?php
namespace MediaWiki\Tests\Logging;

use stdClass;

/**
 * @group Database
 * @covers \MediaWiki\Logging\UnsafeLogFormatter
 * @covers \MediaWiki\Logging\LogFormatterFactory
 */
class UnsafeLogFormatterTest extends LogFormatterTestCase {

	public static function provideUnsafeLogDatabaseRows() {
		// Despite a known log type, this hits UnsafeLogFormatter rather than DeleteLogFormatter
		return [
			[
				[
					'type' => 'delete',
					'action' => 'delete',
					'comment' => '',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => [
						'foo' => new stdClass(),
					],
				],
				[
					'text' => 'User performed unknown action "delete/delete" on Page (This log entry could not be shown, because its parameters contain unsafe serialized data.)',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideUnsafeLogDatabaseRows
	 */
	public function testUnsafeLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
