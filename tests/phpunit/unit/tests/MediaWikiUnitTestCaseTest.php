<?php

use MediaWiki\Config\HashConfig;
use Wikimedia\Services\NoSuchServiceException;

/**
 * Test functionality implemented in the MediaWikiUnitTestCase base class.
 *
 * @covers \MediaWikiUnitTestCase
 */
class MediaWikiUnitTestCaseTest extends MediaWikiUnitTestCase {

	public function testServiceContainer() {
		$config = new HashConfig();
		$cache = new HashBagOStuff();

		$this->setService( 'MainConfig', $config );
		$services = $this->getServiceContainer();

		$this->setService(
			'LocalServerObjectCache',
			static function () use ( $cache ) {
				return $cache;
			}
		);

		$this->assertSame( $config, $services->get( 'MainConfig' ) );
		$this->assertSame( $config, $services->getService( 'MainConfig' ) );
		$this->assertSame( $config, $services->getMainConfig() );

		$this->assertSame( $cache, $services->get( 'LocalServerObjectCache' ) );
		$this->assertSame( $cache, $services->getService( 'LocalServerObjectCache' ) );
		$this->assertSame( $cache, $services->getLocalServerObjectCache() );

		$this->expectException( NoSuchServiceException::class );
		$services->getMessageCache();
	}
}
