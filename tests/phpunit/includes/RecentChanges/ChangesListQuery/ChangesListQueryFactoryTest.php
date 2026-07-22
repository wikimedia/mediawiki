<?php

namespace MediaWiki\Tests\Integration\RecentChanges\ChangesListQuery;

use FactoryArgTestTrait;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQueryFactory;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\ChangesListQueryFactory
 */
class ChangesListQueryFactoryTest extends MediaWikiIntegrationTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return ChangesListQueryFactory::class;
	}

	protected static function getInstanceClass() {
		return ChangesListQuery::class;
	}

	protected static function getExtraClassArgCount(): int {
		// rcStats (in the factory constructor)
		return 1;
	}

	protected function getFactoryMethodName(): string {
		return 'newQuery';
	}

	protected function getIgnoredParamNames(): array {
		return [ 'connectionProvider' ];
	}

	protected function getOverriddenMockValueForParam( $param ) {
		if ( $param->getName() === 'config' ) {
			return [ new ServiceOptions(
				ChangesListQueryFactory::CONSTRUCTOR_OPTIONS,
				$this->getServiceContainer()->getMainConfig()
			) ];
		}
		return [];
	}
}
