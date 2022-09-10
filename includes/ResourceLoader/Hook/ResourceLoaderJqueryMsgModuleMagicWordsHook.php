<?php

namespace MediaWiki\ResourceLoader\Hook;

use MediaWiki\ResourceLoader\Context;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ResourceLoaderJqueryMsgModuleMagicWords" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderJqueryMsgModuleMagicWordsHook {
	/**
	 * Add magic words to the `mediawiki.jqueryMsg` module. The values should be a string,
	 * and they may only vary by what's in the Context.
	 *
	 * This hook is called from ResourceLoaderJqueryMsgModule.
	 *
	 * @since 1.35
	 * @param Context $context
	 * @param string[] &$magicWords Associative array mapping all-caps magic word to a string value
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderJqueryMsgModuleMagicWords(
		Context $context,
		array &$magicWords
	): void;
}
