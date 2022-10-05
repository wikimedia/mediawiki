<?php

namespace MediaWiki\Hook;

use Skin;

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
	 * @param Skin $skin the skin being used
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPreferencesGetLayout( &$useMobileLayout, $skin );
}
