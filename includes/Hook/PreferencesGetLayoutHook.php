<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PreferencesGetLayout" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PreferencesGetLayoutHook {
	/**
	 * Use the hook to check if the preferences will have a mobile or desktop layout.
	 *
	 * @since 1.40
	 * @param bool &$useMobileLayout a boolean which will indicate whether to use
	 * a mobile layout or not
	 * @param string $skinName the name of the skin being used
	 * @param array $skinProperties an associative array that includes skin properties.
	 * A skin property could be one of the following:
	 * - `isResponsive`: Whether a skin can be responsive.
	 * - `getVersion`: Get the version of the skin.
	 * Is an empty array by default
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPreferencesGetLayout( &$useMobileLayout, $skinName, $skinProperties = [] );
}
