<?php
/**
 * Test class for ImageListPagerTest class.
 *
 * Copyright © 2013, Antoine Musso
 * Copyright © 2013, Siebrand Mazeland
 * Copyright © 2013, Wikimedia Foundation Inc.
 *
 * @group Database
 */
class ImageListPagerTest extends \MediaWikiUnitTestCase {
	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage invalid_field
	 * @covers ImageListPager::formatValue
	 */
	public function testFormatValuesThrowException() {
		$page = $this->getMockBuilder( ImageListPager::class )
			->disableOriginalConstructor()
			->setMethods( null )
			->getMock();

		$page->formatValue( 'invalid_field', 'invalid_value' );
	}
}
