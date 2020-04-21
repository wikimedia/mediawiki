<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResourceLoaderJqueryMsgModuleMagicWordsHook {
	/**
	 * Called in
	 * ResourceLoaderJqueryMsgModule to allow adding magic words for jQueryMsg.
	 * The value should be a string, and they can depend only on the
	 * ResourceLoaderContext.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context ResourceLoaderContext
	 * @param ?mixed &$magicWords Associative array mapping all-caps magic word to a string value
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderJqueryMsgModuleMagicWords( $context,
		&$magicWords
	);
}
