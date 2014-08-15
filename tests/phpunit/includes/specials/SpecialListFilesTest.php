<?php
/**
 * Test class for SpecialListFiles class.
 *
 * Copyright © 2013, Antoine Musso
 * Copyright © 2013, Siebrand Mazeland
 * Copyright © 2013, Wikimedia Foundation Inc.
 *
 * @group Database
 */

class SpecialListFilesTest extends MediaWikiTestCase {
	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage invalid_field
	 * @covers ImageListPager::formatValue
	 */
	public function testFormatValuesThrowException() {
		$page = new ImageListPager( RequestContext::getMain() );
		$page->formatValue( 'invalid_field', 'invalid_value' );
	}
}
