<?php

class UploadLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideUploadLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'upload',
					'action' => 'upload',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => array(
						'img_sha1' => 'hash',
						'img_timestamp' => '20150101000000',
					),
				),
				array(
					'text' => 'User uploaded File:File.png',
					'api' => array(
						'img_sha1' => 'hash',
						'img_timestamp' => '2015-01-01T00:00:00Z',
					),
				),
			),

			// Old format without params
			array(
				array(
					'type' => 'upload',
					'action' => 'upload',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => array(),
				),
				array(
					'text' => 'User uploaded File:File.png',
					'api' => array(),
				),
			),
		);
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
		return array(
			// Current format
			array(
				array(
					'type' => 'upload',
					'action' => 'overwrite',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => array(
						'img_sha1' => 'hash',
						'img_timestamp' => '20150101000000',
					),
				),
				array(
					'text' => 'User uploaded a new version of File:File.png',
					'api' => array(
						'img_sha1' => 'hash',
						'img_timestamp' => '2015-01-01T00:00:00Z',
					),
				),
			),

			// Old format without params
			array(
				array(
					'type' => 'upload',
					'action' => 'overwrite',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => array(),
				),
				array(
					'text' => 'User uploaded a new version of File:File.png',
					'api' => array(),
				),
			),
		);
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
		return array(
			// Current format
			array(
				array(
					'type' => 'upload',
					'action' => 'revert',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => array(
						'img_sha1' => 'hash',
						'img_timestamp' => '20150101000000',
					),
				),
				array(
					'text' => 'User uploaded File:File.png',
					'api' => array(
						'img_sha1' => 'hash',
						'img_timestamp' => '2015-01-01T00:00:00Z',
					),
				),
			),

			// Old format without params
			array(
				array(
					'type' => 'upload',
					'action' => 'revert',
					'comment' => 'upload comment',
					'namespace' => NS_FILE,
					'title' => 'File.png',
					'params' => array(),
				),
				array(
					'text' => 'User uploaded File:File.png',
					'api' => array(),
				),
			),
		);
	}

	/**
	 * @dataProvider provideRevertLogDatabaseRows
	 */
	public function testRevertLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
