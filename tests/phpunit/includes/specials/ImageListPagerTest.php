<?php

use MediaWiki\MediaWikiServices;

/**
 * Test class for ImageListPagerTest class.
 *
 * Copyright © 2013, Antoine Musso
 * Copyright © 2013, Siebrand Mazeland
 * Copyright © 2013, Wikimedia Foundation Inc.
 *
 * @group Database
 */
class ImageListPagerTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers ImageListPager::formatValue
	 */
	public function testFormatValuesThrowException() {
		$page = new ImageListPager( RequestContext::getMain(), null, '', false, false,
			MediaWikiServices::getInstance()->getLinkRenderer() );
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( "invalid_field" );
		$page->formatValue( 'invalid_field', 'invalid_value' );
	}
}
