<?php

use MediaWiki\Parser\ParserCacheFactory;

/**
 * @covers \MediaWiki\Parser\ParserCacheFactory
 */
class ParserCacheFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return ParserCacheFactory::class;
	}

	protected static function getInstanceClass() {
		return ParserCache::class;
	}

	protected static function getFactoryMethodName() {
		return 'getInstance';
	}

	protected static function getExtraClassArgCount() {
		// +1 $name
		// -1 $logger
		return 0;
	}

	protected function getIgnoredParamNames() {
		return [ 'hookContainer', 'logger' ];
	}
}
