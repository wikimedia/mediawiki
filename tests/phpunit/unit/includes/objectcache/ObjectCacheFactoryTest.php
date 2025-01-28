<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\NullSpi;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\NoopTracer;

/**
 * @covers \ObjectCacheFactory
 */
class ObjectCacheFactoryTest extends MediaWikiUnitTestCase {
	private function newObjectCacheFactory() {
		return new ObjectCacheFactory(
			$this->createMock( ServiceOptions::class ),
			StatsFactory::newNull(),
			new NullSpi(),
			static function () {
			},
			'testWikiId',
			new NoopTracer()
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
			'class' => HashBagOStuff::class,
			'args' => [ 'foo', 'bar' ],
		] );

		$this->assertInstanceOf( HashBagOStuff::class, $objCache );
	}

	public function testShouldPassTracer(): void {
		$factory = $this->newObjectCacheFactory();

		$cache = $factory->newFromParams( [
			'factory' => function ( array $params ): HashBagOStuff {
				$this->assertInstanceOf( NoopTracer::class, $params['telemetry'] );
				return new HashBagOStuff();
			}
		] );

		$this->assertInstanceOf( HashBagOStuff::class, $cache );
	}
}
