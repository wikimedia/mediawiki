<?php

namespace MediaWiki\ResourceLoader\Hook;

use ResourceLoaderContext;

/**
 * @stable to implement
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderJqueryMsgModuleMagicWordsHook {
	/**
	 * Add magic words to the `mediawiki.jqueryMsg` module. The values should be a string,
	 * and they may only vary by what's in the ResourceLoaderContext.
	 *
	 * This hook is called from ResourceLoaderJqueryMsgModule.
	 *
	 * @since 1.35
	 * @param ResourceLoaderContext $context
	 * @param string[] &$magicWords Associative array mapping all-caps magic word to a string value
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderJqueryMsgModuleMagicWords(
		ResourceLoaderContext $context,
		array &$magicWords
	) : void;
}
