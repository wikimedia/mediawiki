<?php

use MediaWiki\Cache\LinkBatchFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @group Cache
 * @covers \MediaWiki\Cache\LinkBatchFactory
 */
class LinkBatchFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return LinkBatchFactory::class;
	}

	protected static function getInstanceClass() {
		return LinkBatch::class;
	}

	protected static function getExtraClassArgCount() {
		// $arr
		return 1;
	}

	public function testNewLinkBatch() {
		$factory = new LinkBatchFactory(
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->createMock( ILoadBalancer::class )
		);

		$linkBatch = $factory->newLinkBatch( [
			new TitleValue( NS_MAIN, 'Foo' ),
			new TitleValue( NS_TALK, 'Bar' ),
		] );

		$this->assertFalse( $linkBatch->isEmpty() );
		$this->assertSame( 2, $linkBatch->getSize() );
	}
}
