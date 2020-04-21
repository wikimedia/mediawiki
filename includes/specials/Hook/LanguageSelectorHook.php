<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LanguageSelectorHook {
	/**
	 * Hook to change the language selector available on a page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $out The output page.
	 * @param ?mixed $cssClassName CSS class name of the language selector.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLanguageSelector( $out, $cssClassName );
}
