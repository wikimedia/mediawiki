<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use PHPUnit\Framework\Test;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MediaWikiPHPUnitTest::startTest" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MediaWikiPHPUnitTest__startTestHook {
	/**
	 * This hook is called before each PHPUnit test starts.
	 *
	 * @since 1.35
	 *
	 * @param Test $test
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiPHPUnitTest__startTest( $test );
}
