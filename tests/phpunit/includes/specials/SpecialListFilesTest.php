<?php
/**
 * Test class for SpecialListFiles class.
 *
 * Copyright © 2013, Antoine Musso
 * Copyright © 2013, Siebrand Mazeland
 * Copyright © 2013, Wikimedia Foundation Inc.
 *
 */

/**
 * @covers SpecialPreferences
 */
class SpecialListFilesTest extends MediaWikiTestCase {
	public $inOut = array(
		'thumb-not-found' => array( 'thumb', '&amp;', '&amp;amp;' ),
		'img_timestamp' => array( 'img_timestamp', '1388494463', '12:54, 31 December 2013' ),
		// This will probably fail in test environment because there is no file called Test.jpg.
		// This is actually testing  Linker::linkKnown and Message. Why needed?
		'img_name' => array(
			'img_name',
			'Test.jpg',
			'<a href="/wiki/File:Test.jpg" title=File:Test.jpg>Test.jpg</a> (<a href="/w/images/b/bd/Test.jpg">file</a>)'
		),
		'img_user_text' => array( 'img_user_text', 'Some username', 'Some username' ),
		//'img_size' => array( 'thumb', 'thumb' ),
		//'img_description' => array( 'thumb', 'thumb' ),
		//'count' => array( 'thumb', 'thumb' ),
		//'top' => array( 'thumb', 'thumb' ),
	);

	/**
	 * @expectedException MWException
	 * @covers ImageListPager::formatValue
	 */
	public function testFormatValuesThrowException() {
		$page = new ImageListPager( RequestContext::getMain() );
		$page->formatValue( 'invalid_field', 'invalid_value' );
	}
}
