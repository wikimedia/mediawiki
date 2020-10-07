<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use PHPUnit\Framework\Test;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MediaWikiPHPUnitTest__endTestHook {
	/**
	 * This hook is called after each PHPUnit test completes.
	 *
	 * @since 1.35
	 *
	 * @param Test $test
	 * @param int $time Execution time in seconds
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiPHPUnitTest__endTest( $test, $time );
}
