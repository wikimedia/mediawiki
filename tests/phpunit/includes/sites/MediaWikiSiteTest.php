<?php

/**
 * Tests for the MediaWikiSite class.
 *
 * @file
 * @since 1.20
 *
 * @ingroup Sites
 * @ingroup Test
 *
 * @group Sites
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiSiteTest extends \MediaWikiTestCase {

	public function testFactoryConstruction() {
		$this->assertInstanceOf( 'MediaWikiSite', Sites::newSite( array( 'type' => Site::TYPE_MEDIAWIKI ) ) );
		$this->assertInstanceOf( 'Site', Sites::newSite( array( 'type' => Site::TYPE_MEDIAWIKI ) ) );
	}

}
