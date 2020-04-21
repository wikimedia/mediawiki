<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MediaWikiPHPUnitTest__startTestHook {
	/**
	 * Before each PHPUnit test starts
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $test PHPUnit\Framework\Test object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiPHPUnitTest__startTest( $test );
}
