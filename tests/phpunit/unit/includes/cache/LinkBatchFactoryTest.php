<?php

use MediaWiki\Cache\GenderCache;
use MediaWiki\Cache\LinkBatch;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Cache\LinkCache;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use Wikimedia\Rdbms\IConnectionProvider;

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
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);

		$linkBatch = $factory->newLinkBatch( [
			new TitleValue( NS_MAIN, 'Foo' ),
			PageReferenceValue::localReference( NS_TALK, 'Bar' ),
		] );

		$this->assertFalse( $linkBatch->isEmpty() );
		$this->assertSame( 2, $linkBatch->getSize() );
	}
}
