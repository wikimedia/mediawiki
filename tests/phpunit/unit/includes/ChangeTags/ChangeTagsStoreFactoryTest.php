<?php

namespace MediaWiki\Tests\Unit\ChangeTags;

use FactoryArgTestTrait;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\ChangeTags\ChangeTagsStoreFactory;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\ChangeTags\ChangeTagsStoreFactory
 */
class ChangeTagsStoreFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return ChangeTagsStoreFactory::class;
	}

	protected static function getInstanceClass() {
		return ChangeTagsStore::class;
	}

	protected static function getExtraClassArgCount(): int {
		// $wiki
		return 1;
	}

	protected function getFactoryMethodName(): string {
		return 'getChangeTagsStore';
	}

	protected function getIgnoredParamNames(): array {
		return [ 'changeTagDefStoreFactory' ];
	}

	protected function getOverriddenMockValueForParam( $param ) {
		if ( $param->getName() === 'wiki' ) {
			return [ false ];
		}
		if ( $param->getType() && $param->getType()->getName() === NameTableStoreFactory::class ) {
			$mock = $this->createNoOpMock( NameTableStoreFactory::class, [ 'getChangeTagDef' ] );
			$mock->expects( $this->once() )
				->method( 'getChangeTagDef' )
				->willReturn( $this->createNoOpMock( NameTableStore::class ) );
			return [ $mock ];
		}
		return [];
	}
}
