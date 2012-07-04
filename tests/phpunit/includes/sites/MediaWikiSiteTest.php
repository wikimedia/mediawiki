<?php

namespace MW\Test;
use MW\Sites as Sites;

/**
 * Tests for the MW\MediaWikiSite class.
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
		$this->assertInstanceOf( 'MW\MediaWikiSite', Sites::newSite( array( 'type' => SITE_TYPE_MEDIAWIKI ) ) );
		$this->assertInstanceOf( 'MW\Site', Sites::newSite( array( 'type' => SITE_TYPE_MEDIAWIKI ) ) );
	}

}
