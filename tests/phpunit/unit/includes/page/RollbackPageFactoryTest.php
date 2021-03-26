<?php

namespace MediaWiki\Tests\Unit\Page;

use FactoryArgTestTrait;
use HashConfig;
use MediaWiki\Page\PageCommandFactory;
use MediaWiki\Page\RollbackPage;
use MediaWikiUnitTestCase;
use ReflectionParameter;

/**
 * @covers \MediaWiki\Page\PageCommandFactory
 * @package MediaWiki\Tests\Unit\Page
 */
class RollbackPageFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return PageCommandFactory::class;
	}

	protected static function getInstanceClass() {
		return RollbackPage::class;
	}

	protected static function getExtraClassArgCount() {
		// $page, $performer, $forUser - ignored params
		return -2;
	}

	protected function getOverriddenMockValueForParam( ReflectionParameter $param ) {
		if ( $param->getName() === 'config' ) {
			return [ new HashConfig(
				array_fill_keys( RollbackPage::CONSTRUCTOR_OPTIONS, 'test' )
			) ];
		}
		return [];
	}

	protected function getIgnoredParamNames() {
		return [
			'config',
			'namespaceInfo',
			'watchedItemStore',
			'repoGroup',
			'contentHandlerFactory',
			'spamChecker'
		];
	}
}
