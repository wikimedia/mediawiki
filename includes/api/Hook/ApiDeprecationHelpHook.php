<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiDeprecationHelpHook {
	/**
	 * Add messages to the 'deprecation-help' warning generated
	 * from ApiBase::addDeprecation().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$msgs Message[] Messages to include in the help. Multiple messages will be
	 *   joined with spaces.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiDeprecationHelp( &$msgs );
}
