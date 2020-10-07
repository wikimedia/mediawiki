<?php

namespace MediaWiki\Api\Hook;

use Message;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiDeprecationHelpHook {
	/**
	 * Use this hook to add messages to the 'deprecation-help' warning generated
	 * from ApiBase::addDeprecation().
	 *
	 * @since 1.35
	 *
	 * @param Message[] &$msgs Messages to include in the help. Multiple messages will be
	 *   joined with spaces.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiDeprecationHelp( &$msgs );
}
