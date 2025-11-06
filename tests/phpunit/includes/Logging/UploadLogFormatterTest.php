<?php
namespace MediaWiki\Tests\Logging;

/**
 * @covers \MediaWiki\Logging\UploadLogFormatter
 */
class UploadLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideUploadLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'upload',
					'action' => 'upload',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => [
						'img_sha1' => 'hash',
						'img_timestamp' => '20150101000000',
					],
				],
				[
					'text' => 'User uploaded File:File.png',
					'api' => [
						'img_sha1' => 'hash',
						'img_timestamp' => '2015-01-01T00:00:00Z',
					],
				],
			],

			// Old format without params
			[
				[
					'type' => 'upload',
					'action' => 'upload',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => [],
				],
				[
					'text' => 'User uploaded File:File.png',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideUploadLogDatabaseRows
	 */
	public function testUploadLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideOverwriteLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'upload',
					'action' => 'overwrite',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => [
						'img_sha1' => 'hash',
						'img_timestamp' => '20150101000000',
					],
				],
				[
					'text' => 'User uploaded a new version of File:File.png',
					'api' => [
						'img_sha1' => 'hash',
						'img_timestamp' => '2015-01-01T00:00:00Z',
					],
				],
			],

			// Old format without params
			[
				[
					'type' => 'upload',
					'action' => 'overwrite',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => [],
				],
				[
					'text' => 'User uploaded a new version of File:File.png',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideOverwriteLogDatabaseRows
	 */
	public function testOverwriteLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideRevertLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'upload',
					'action' => 'revert',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => [
						'img_sha1' => 'hash',
						'img_timestamp' => '20150101000000',
					],
				],
				[
					'text' => 'User reverted File:File.png to an old version',
					'api' => [
						'img_sha1' => 'hash',
						'img_timestamp' => '2015-01-01T00:00:00Z',
					],
				],
			],

			// Old format without params
			[
				[
					'type' => 'upload',
					'action' => 'revert',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => [],
				],
				[
					'text' => 'User reverted File:File.png to an old version',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideRevertLogDatabaseRows
	 */
	public function testRevertLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
