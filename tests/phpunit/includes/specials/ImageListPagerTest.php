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
class ImageListPagerTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers ImageListPager::formatValue
	 */
	public function testFormatValuesThrowException() {
		$services = $this->getServiceContainer();
		$page = new ImageListPager(
			RequestContext::getMain(),
			$services->getCommentStore(),
			$services->getLinkRenderer(),
			$services->getDBLoadBalancer(),
			$services->getRepoGroup(),
			$services->getUserCache(),
			$services->getUserNameUtils(),
			$services->getCommentFormatter(),
			null,
			'',
			false,
			false
		);
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( "invalid_field" );
		$page->formatValue( 'invalid_field', 'invalid_value' );
	}
}
