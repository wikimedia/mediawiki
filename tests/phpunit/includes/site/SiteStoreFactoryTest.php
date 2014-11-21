<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.25
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @covers SiteStoreFactory
 * @group Site
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SiteStoreFactoryTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider getSiteStoreProvider
	 */
	public function testGetSiteStore( $expectedClass, Config $config, $message ) {
		$siteStoreFactory = new SiteStoreFactory( $config );
		$this->assertInstanceOf( $expectedClass, $siteStoreFactory->getSiteStore(), $message );
	}

	public function getSiteStoreProvider() {
		$cachingConfig = new HashConfig();
		$cachingConfig->set( 'SitesCacheFile', '/tmp/sites.json' );
		$cachingConfig->set( 'SitesCacheManualRecache', true );

		$manualRecacheConfig = new HashConfig();
		$manualRecacheConfig->set( 'SitesCacheFile', '/tmp/sites.json' );
		$manualRecacheConfig->set( 'SitesCacheManualRecache', false );

		$noCachingConfig = new HashConfig();
		$noCachingConfig->set( 'SitesCacheFile', false );
		$noCachingConfig->set( 'SitesCacheManualRecache', false );

		return array(
			array( 'SiteStore', GlobalVarConfig::newInstance(), 'global config, setting exists' ),
			array( 'CachingFileSiteStore', $cachingConfig, 'caching config' ),
			array( 'CachingFileSiteStore', $manualRecacheConfig, 'manual recache config' ),
			array( 'SiteSQLStore', $noCachingConfig, 'no caching config' )
		);
	}

}
