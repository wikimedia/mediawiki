<?php

namespace MediaWiki\Hook;

use IContextSource;
use Skin;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RequestContextCreateSkin" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface RequestContextCreateSkinHook {
	/**
	 * This hook is called when RequestContext::getSkin creates a skin instance.
	 * Use this hook to override what skin is used in certain contexts.
	 *
	 * @since 1.35
	 *
	 * @param IContextSource $context RequestContext the skin is being created for
	 * @param null|string|Skin &$skin A variable reference you may modify to set
	 *        a Skin instance or string key on to override the skin that will be
	 *        used for the context.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRequestContextCreateSkin( $context, &$skin );
}
