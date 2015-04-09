<?php

class PageLangLogFormatterTest extends LogFormatterTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			// Enable the feature switch
			'wgPageLanguageUseDB' => true,
			// Disable cldr extension
			'wgHooks' => array(),
		) );
	}

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
					'text' => 'User changed page language for Page from English (en) to Deutsch (de) [default].',
					'api' => array( 'oldlanguage' => 'en', 'newlanguage' => 'de[def]' ),
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
