<?php

class PageLangLogFormatterTest extends LogFormatterTestCase {

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function providePageLangLogDatabaseRows() {
		return array(
			// Current format
			array(
				array(
					'type' => 'pagelang',
					'action' => 'pagelang',
					'comment' => 'page lang comment',
					'namespace' => NS_MAIN,
					'title' => 'Page',
					'params' => array(
						'4::oldlanguage' => 'en',
						'5::newlanguage' => 'de[def]',
					),
				),
				array(
					'key' => 'logentry-pagelang-pagelang',
					'paramCount' => 5,
				),
			),
		);
	}

	/**
	 * @dataProvider providePageLangLogDatabaseRows
	 */
	public function testPageLangLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
