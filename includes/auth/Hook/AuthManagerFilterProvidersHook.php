<?php

namespace MediaWiki\Auth\Hook;

use MediaWiki\MainConfigSchema;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AuthManagerFilterProviders" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface AuthManagerFilterProvidersHook {

	/**
	 * Filter the list of authentication available providers. Providers removed from the
	 * list will be disabled for the current request, and any authentication process started
	 * from the current request.
	 *
	 * Hook handlers don't have to always return the same result for the given configuration
	 * (can depend on the request, e.g. feature flags) but they do have to be consistent
	 * within an authentication process that spans multiple requests.
	 *
	 * @since 1.43
	 *
	 * @param bool[][] &$providers An array with three sub-arrays: 'preauth', 'primaryauth',
	 *   'secondaryauth'. Each field in the subarrays is a map of <provider key> => true.
	 *   (The provider key is the same array key that's used in $wgAuthManagerAutoConfig or
	 *   $wgAuthManagerConfig). Unsetting a field or setting its value to falsy disables the
	 *   corresponding provider.
	 * @phpcs:ignore Generic.Files.LineLength.TooLong
	 * @phan-param array{preauth:array<string,true>,primaryauth:array<string,true>,secondaryauth:array<string,true>} $providers
	 * @return void This hook must not abort, it must return no value
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/AuthManagerFilterProviders
	 * @see MainConfigSchema::AuthManagerAutoConfig
	 */
	public function onAuthManagerFilterProviders( array &$providers ): void;

}
