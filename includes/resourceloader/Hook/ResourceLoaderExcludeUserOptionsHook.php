<?php

namespace MediaWiki\ResourceLoader\Hook;

use ResourceLoaderContext;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ResourceLoaderExcludeUserOptions" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderExcludeUserOptionsHook {

	/**
	 * Exclude a user option from the preloaded data for client-side mw.user.options.
	 *
	 * This hook is called on every index.php pageview (via ResourceLoaderUserOptionsModule),
	 * and when building responses for the "mediawiki.base" module. Avoid database queries
	 * or other expensive operations as that would increase page load time.
	 *
	 * Use this hook to optimize pageview HTML size by omitting user preference
	 * values from the export JavaScript data for `mw.user.options`. For example,
	 * when an extension stores large values in a user preference, and rarely or never
	 * needs these client-side, you can exclude it via this hook. (T251994)
	 *
	 * This will exclude both the default value (via mediawiki.base module) and
	 * the current user's value (via pageview HTML).
	 *
	 * @since 1.38
	 * @param array &$keysToExclude
	 * @param ResourceLoaderContext $context
	 * @return void
	 */
	public function onResourceLoaderExcludeUserOptions( array &$keysToExclude, ResourceLoaderContext $context ): void;
}
