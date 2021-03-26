<?php

use MediaWiki\Page\PageCommandFactory;

/**
 * @covers MediaWiki\Page\PageCommandFactory
 */
class MovePageFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return PageCommandFactory::class;
	}

	protected static function getInstanceClass() {
		return MovePage::class;
	}

	protected static function getExtraClassArgCount() {
		// $to + $from - $readOnlyMode - $titleFormatter - $actorMigration
		return -1;
	}

	protected function getOverriddenMockValueForParam( ReflectionParameter $param ) {
		if ( $param->getName() === 'config' ) {
			return [ new HashConfig(
				array_fill_keys( MovePage::CONSTRUCTOR_OPTIONS, 'test' )
			) ];
		}
		return [];
	}

	protected function getIgnoredParamNames() {
		return [ 'readOnlyMode', 'config', 'titleFormatter', 'actorMigration' ];
	}
}
