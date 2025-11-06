<?php
namespace MediaWiki\Tests\Logging;

/**
 * @covers \MediaWiki\Logging\MergeLogFormatter
 */
class MergeLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideMergeLogDatabaseRows() {
		return [
			// Merge with start time
			[
				[
					'type' => 'merge',
					'action' => 'merge',
					'comment' => 'Merge comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'4::dest' => 'NewPage',
						'5::mergepoint' => '20140804160710',
						'6::mergerevid' => '1234',
						'7::mergestart' => '20130804160710',
						'8::mergestartid' => '123'
					],
				],
				[
					'text' => 'User merged OldPage into NewPage (revisions from 16:07, 4 August 2013 to 16:07, 4 August 2014)',
					'api' => [
						'mergerevid' => '1234',
						'mergestartid' => '123',
						'dest_ns' => 0,
						'dest_title' => 'NewPage',
						'mergepoint' => '2014-08-04T16:07:10Z',
						'mergestart' => '2013-08-04T16:07:10Z'
					],
				],
			],

			// Merge without start time
			[
				[
					'type' => 'merge',
					'action' => 'merge',
					'comment' => 'Merge comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'4::dest' => 'NewPage',
						'5::mergepoint' => '20140804160710',
						'6::mergerevid' => '1234'
					],
				],
				[
					'text' => 'User merged OldPage into NewPage (revisions up to 16:07, 4 August 2014)',
					'api' => [
						'mergerevid' => '1234',
						'dest_ns' => 0,
						'dest_title' => 'NewPage',
						'mergepoint' => '2014-08-04T16:07:10Z',
					],
				],
			],
			// Same format without a revid
			[
				[
					'type' => 'merge',
					'action' => 'merge',
					'comment' => 'Merge comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'4::dest' => 'NewPage',
						'5::mergepoint' => '20140804160710',
					],
				],
				[
					'text' => 'User merged OldPage into NewPage (revisions up to 16:07, 4 August 2014)',
					'api' => [
						'dest_ns' => 0,
						'dest_title' => 'NewPage',
						'mergepoint' => '2014-08-04T16:07:10Z',
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'merge',
					'action' => 'merge',
					'comment' => 'merge comment',
					'namespace' => NS_MAIN,
					'title' => 'OldPage',
					'params' => [
						'NewPage',
						'20140804160710',
					],
				],
				[
					'legacy' => true,
					'text' => 'User merged OldPage into NewPage (revisions up to 16:07, 4 August 2014)',
					'api' => [
						'dest_ns' => 0,
						'dest_title' => 'NewPage',
						'mergepoint' => '2014-08-04T16:07:10Z',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideMergeLogDatabaseRows
	 */
	public function testMergeLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
