<?php

namespace MediaWiki\Test\Unit\PageEdit;

use FactoryArgTestTrait;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\PageEdit\PageEdit;
use MediaWiki\PageEdit\PageEditFactory;
use MediaWikiUnitTestCase;
use ReflectionParameter;

/**
 * @covers \MediaWiki\PageEdit\PageEditFactory
 */
class PageEditFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	/** @inheritDoc */
	protected static function getFactoryClass(): string {
		return PageEditFactory::class;
	}

	/** @inheritDoc */
	protected static function getInstanceClass(): string {
		return PageEdit::class;
	}

	/** @inheritDoc */
	protected static function getExtraClassArgCount(): int {
		return 1;
	}

	/** @inheritDoc */
	protected function getOverriddenMockValueForParam( ReflectionParameter $param ): array {
		if ( $param->getName() === 'options' ) {
			return [ new ServiceOptions(
				PageEditFactory::CONSTRUCTOR_OPTIONS,
				[
					MainConfigNames::EnableWatchlistLabels => false,
					MainConfigNames::UseNPPatrol => true,
					MainConfigNames::UseRCPatrol => true,
				]
			) ];
		}
		return [];
	}

}
