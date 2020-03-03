<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RequestContextCreateSkinHook {
	/**
	 * Called when RequestContext::getSkin creates a skin
	 * instance. Can be used by an extension override what skin is used in certain
	 * contexts.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context (IContextSource) The RequestContext the skin is being created for.
	 * @param ?mixed &$skin A variable reference you may set a Skin instance or string key on to
	 *   override the skin that will be used for the context.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRequestContextCreateSkin( $context, &$skin );
}
