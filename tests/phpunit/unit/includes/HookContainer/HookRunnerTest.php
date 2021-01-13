<?php

namespace MediaWiki\Tests\HookContainer;

use MediaWiki\Api\ApiHookRunner;
use MediaWiki\HookContainer\HookRunner;

/**
 * Tests that all arguments passed into HookRunner are passed along to HookContainer.
 * @covers \MediaWiki\HookContainer\HookRunner
 * @covers \MediaWiki\Api\ApiHookRunner
 * @package MediaWiki\Tests\HookContainer
 */
class HookRunnerTest extends HookRunnerTestBase {

	public function provideHookRunners() {
		yield ApiHookRunner::class => [ ApiHookRunner::class ];
		yield HookRunner::class => [ HookRunner::class ];
	}
}
