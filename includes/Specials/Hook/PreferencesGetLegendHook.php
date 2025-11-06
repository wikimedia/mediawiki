<?php

namespace MediaWiki\Hook;

use MediaWiki\HTMLForm\HTMLForm;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PreferencesGetLegend" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PreferencesGetLegendHook {
	/**
	 * Use the hook to override the text used for the <legend> of a preferences section.
	 *
	 * @since 1.35
	 *
	 * @param HTMLForm $form the HTMLForm object. This is a ContextSource as well
	 * @param string $key the section name
	 * @param string &$legend the legend text. Defaults to wfMessage( "prefs-$key" )->text() but may
	 *   be overridden
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPreferencesGetLegend( $form, $key, &$legend );
}
