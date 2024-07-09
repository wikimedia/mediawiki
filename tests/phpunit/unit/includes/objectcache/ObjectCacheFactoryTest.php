<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\Spi;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Stats\StatsFactory;

/**
 * @covers ObjectCacheFactory
 */
class ObjectCacheFactoryTest extends MediaWikiUnitTestCase {
	private function newObjectCacheFactory() {
		return new ObjectCacheFactory(
			$this->createMock( ServiceOptions::class ),
			$this->createMock( StatsFactory::class ),
			$this->createMock( Spi::class ),
			static function () {
			},
			'testWikiId'
		);
	}

	public function testNewObjectCacheFactory() {
		$this->assertInstanceOf(
			ObjectCacheFactory::class,
			$this->newObjectCacheFactory()
		);
	}

	public function testNewFromParams() {
		$factory = $this->newObjectCacheFactory();

		$objCache = $factory->newFromParams( [
			'class' => 'HashBagOStuff',
			'args' => [ 'foo', 'bar' ],
		] );

		$this->assertInstanceOf( HashBagOStuff::class, $objCache );
	}
}
