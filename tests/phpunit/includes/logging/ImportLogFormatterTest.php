<?php

class ImportLogFormatterTest extends LogFormatterTestCase {

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
					'type' => 'import',
					'action' => 'upload',
					'comment' => 'upload comment',
					'namespace' => NS_MAIN,
					'title' => 'ImportPage',
					'params' => array(
						'4:number:count' => '1',
					),
				),
				array(
					'text' => 'User imported ImportPage with 1 revision by file upload',
					'api' => array(
						'count' => 1,
					),
				),
			),

			// old format - without details
			array(
				array(
					'type' => 'import',
					'action' => 'upload',
					'comment' => '1 revision: import comment',
					'namespace' => NS_MAIN,
					'title' => 'ImportPage',
					'params' => array(),
				),
				array(
					'text' => 'User imported ImportPage by file upload',
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
	public static function provideInterwikiLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'import',
					'action' => 'interwiki',
					'comment' => 'interwiki comment',
					'namespace' => NS_MAIN,
					'title' => 'ImportPage',
					'params' => array(
						'4:number:count' => '1',
						'5:title-link:interwiki' => 'importiw:PageImport',
					),
				),
				array(
					'text' => 'User imported ImportPage with 1 revision from importiw:PageImport',
					'api' => array(
						'count' => 1,
						'interwiki_ns' => 0,
						'interwiki_title' => 'importiw:PageImport',
					),
				),
			),

			// old format - without details
			array(
				array(
					'type' => 'import',
					'action' => 'interwiki',
					'comment' => '1 revision from importiw:PageImport: interwiki comment',
					'namespace' => NS_MAIN,
					'title' => 'ImportPage',
					'params' => array(),
				),
				array(
					'text' => 'User imported ImportPage from another wiki',
					'api' => array(),
				),
			),
		);
	}

	/**
	 * @dataProvider provideInterwikiLogDatabaseRows
	 */
	public function testInterwikiLogDatabaseRows( $row, $extra ) {
		// Setup importiw: as interwiki prefix
		$this->setMwGlobals( 'wgHooks', array(
			'InterwikiLoadPrefix' => array(
				function ( $prefix, &$data ) {
					if ( $prefix == 'importiw' ) {
						$data = array( 'iw_url' => 'wikipedia' );
					}
					return false;
				}
			)
		) );

		$this->doTestLogFormatter( $row, $extra );
	}
}
