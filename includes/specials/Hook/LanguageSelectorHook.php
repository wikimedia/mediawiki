<?php

namespace MediaWiki\Hook;

use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LanguageSelectorHook {
	/**
	 * Use this hook to change the language selector available on a page.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $out The output page.
	 * @param string $cssClassName CSS class name of the language selector.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLanguageSelector( $out, $cssClassName );
}
