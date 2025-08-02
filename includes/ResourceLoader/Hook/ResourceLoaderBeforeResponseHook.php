<?php

namespace MediaWiki\ResourceLoader\Hook;

use MediaWiki\ResourceLoader\Context;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ResourceLoaderBeforeHeaders" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderBeforeResponseHook {
	/**
	 * Add extra HTTP response headers for the given request context.
	 *
	 * These headers are sent regardless of status (200 OK, or 304 Not Modified)
	 * and content type (CSS, JS, Image, SourceMap).
	 *
	 * This hook is called from ResourceLoaderEntryPoint.
	 *
	 * @since 1.45
	 * @param Context $context
	 * @param string[] &$extraHeaders
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderBeforeResponse( Context $context, array &$extraHeaders ): void;
}
