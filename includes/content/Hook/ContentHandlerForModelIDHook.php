<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContentHandlerForModelIDHook {
	/**
	 * Called when a ContentHandler is requested for
	 * a given content model name, but no entry for that model exists in
	 * $wgContentHandlers.
	 * Note: if your extension implements additional models via this hook, please
	 * use GetContentModels hook to make them known to core.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $modeName the requested content model name
	 * @param ?mixed &$handler set this to a ContentHandler object, if desired.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentHandlerForModelID( $modeName, &$handler );
}
