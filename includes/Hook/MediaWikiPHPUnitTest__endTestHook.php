<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MediaWikiPHPUnitTest__endTestHook {
	/**
	 * After each PHPUnit test completes
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $test PHPUnit\Framework\Test object
	 * @param ?mixed $time The execution time in seconds
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiPHPUnitTest__endTest( $test, $time );
}
