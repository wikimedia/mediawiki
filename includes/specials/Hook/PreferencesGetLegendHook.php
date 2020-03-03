<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PreferencesGetLegendHook {
	/**
	 * Override the text used for the <legend> of a
	 * preferences section.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $form the HTMLForm object. This is a ContextSource as well
	 * @param ?mixed $key the section name
	 * @param ?mixed &$legend the legend text. Defaults to wfMessage( "prefs-$key" )->text() but may
	 *   be overridden
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPreferencesGetLegend( $form, $key, &$legend );
}
