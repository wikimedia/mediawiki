<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserOptionsRegisterHook {
	/**
	 * Use this hook to register additional parser options. Note that if you
	 * change the default value for an option, all existing parser cache entries will
	 * be invalid. To avoid bugs, you'll need to handle that somehow (e.g. with the
	 * RejectParserCacheValue hook) because MediaWiki won't do it for you.
	 *
	 * @since 1.35
	 *
	 * @param array &$defaults Set the default value for your option here
	 * @param array &$inCacheKey To fragment the parser cache on your option, set a truthy value
	 *   in this array, with the key being the option name.
	 * @param array &$lazyLoad To lazy-initialize your option, set it null in $defaults and set a
	 *   callable in this array, with the key being the option name. The callable is passed
	 *   the ParserOptions object and the option name.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserOptionsRegister( &$defaults, &$inCacheKey, &$lazyLoad );
}
