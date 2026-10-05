<?php

namespace MediaWiki\Tests\HookContainer;

use MediaWiki\Api\ApiHookRunner;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\Specials\Hook\SpecialWhatLinksHereBacklinkQueryData;
use ReflectionNamedType;
use ReflectionParameter;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Tests that all arguments passed into HookRunner are passed along to HookContainer.
 * @covers \MediaWiki\HookContainer\HookRunner
 * @covers \MediaWiki\Api\ApiHookRunner
 * @covers \MediaWiki\ResourceLoader\HookRunner
 */
class HookRunnerTest extends HookRunnerTestBase {

	public static function provideHookRunners() {
		yield ApiHookRunner::class => [ ApiHookRunner::class ];
		yield HookRunner::class => [ HookRunner::class ];
		yield RL\HookRunner::class => [ RL\HookRunner::class ];
	}

	protected function getMockedParamValue( ReflectionParameter $param ) {
		$type = $param->getType();
		if ( $type instanceof ReflectionNamedType && $type->getName() === SpecialWhatLinksHereBacklinkQueryData::class ) {
			return new SpecialWhatLinksHereBacklinkQueryData(
				$this->createMock( IReadableDatabase::class ), 'pagelinks', 'pl_from', []
			);
		}
		return parent::getMockedParamValue( $param );
	}

	protected static array $unabortableHooksNotReturningVoid = [
		'onGetAllBlockActions',
		'onSpecialLogResolveLogType',
		'onSpecialPrefixIndexGetFormFilters',
		'onSpecialPrefixIndexQuery',
		'onSpecialWhatLinksHereQuery',
	];

	protected static array $voidMethodsNotUnabortableHooks = [
		'onApiLogFeatureUsage',
		'onContributeCards',
		'onRecentChangesPurgeRows',
	];

}
