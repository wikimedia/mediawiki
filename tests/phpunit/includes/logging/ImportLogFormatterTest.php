<?php

/**
 * @covers ImportLogFormatter
 */
class ImportLogFormatterTest extends LogFormatterTestCase {

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
					'type' => 'import',
					'action' => 'upload',
					'comment' => 'upload comment',
					'namespace' => NS_MAIN,
					'title' => 'ImportPage',
					'params' => [
						'4:number:count' => '1',
					],
				],
				[
					'text' => 'User imported ImportPage by file upload (1 revision)',
					'api' => [
						'count' => 1,
					],
				],
			],

			// old format - without details
			[
				[
					'type' => 'import',
					'action' => 'upload',
					'comment' => '1 revision: import comment',
					'namespace' => NS_MAIN,
					'title' => 'ImportPage',
					'params' => [],
				],
				[
					'text' => 'User imported ImportPage by file upload',
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
	public static function provideInterwikiLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'import',
					'action' => 'interwiki',
					'comment' => 'interwiki comment',
					'namespace' => NS_MAIN,
					'title' => 'ImportPage',
					'params' => [
						'4:number:count' => '1',
						'5:title-link:interwiki' => 'importiw:PageImport',
					],
				],
				[
					'text' => 'User imported ImportPage from importiw:PageImport (1 revision)',
					'api' => [
						'count' => 1,
						'interwiki_ns' => 0,
						'interwiki_title' => 'importiw:PageImport',
					],
				],
			],

			// old format - without details
			[
				[
					'type' => 'import',
					'action' => 'interwiki',
					'comment' => '1 revision from importiw:PageImport: interwiki comment',
					'namespace' => NS_MAIN,
					'title' => 'ImportPage',
					'params' => [],
				],
				[
					'text' => 'User imported ImportPage from another wiki',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideInterwikiLogDatabaseRows
	 */
	public function testInterwikiLogDatabaseRows( $row, $extra ) {
		// Setup importiw: as interwiki prefix
		$this->setMwGlobals( 'wgHooks', [
			'InterwikiLoadPrefix' => [
				function ( $prefix, &$data ) {
					if ( $prefix == 'importiw' ) {
						$data = [ 'iw_url' => 'wikipedia' ];
					}
					return false;
				}
			]
		] );

		$this->doTestLogFormatter( $row, $extra );
	}
}
