<?php

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
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

	protected function getOverriddenMockValueForParam( ReflectionParameter $param ) {
		if ( $param->getName() === 'initialItems' ) {
			return [ [] ];
		}
		return [];
	}

	public function testNewLinkBatch() {
		$factory = new LinkBatchFactory(
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->createMock( ILoadBalancer::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);

		$linkBatch = $factory->newLinkBatch( [
			new TitleValue( NS_MAIN, 'Foo' ),
			new PageReferenceValue( NS_TALK, 'Bar', PageReference::LOCAL ),
		] );

		$this->assertFalse( $linkBatch->isEmpty() );
		$this->assertSame( 2, $linkBatch->getSize() );
	}
}
