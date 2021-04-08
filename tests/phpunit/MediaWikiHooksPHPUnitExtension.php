<?php

use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\BeforeTestHook;

/**
 * Runs a hook whenever a test starts or ends
 */
class MediaWikiHooksPHPUnitExtension implements BeforeTestHook, AfterTestHook {
	/** @inheritDoc */
	public function executeBeforeTest( string $test ): void {
		Hooks::runner()->onMediaWikiPHPUnitTest__startTest( $test );
	}

	/** @inheritDoc */
	public function executeAfterTest( string $test, float $time ): void {
		Hooks::runner()->onMediaWikiPHPUnitTest__endTest( $test, $time );
	}

}
