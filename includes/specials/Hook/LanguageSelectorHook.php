<?php

namespace MediaWiki\Hook;

use MediaWiki\Output\OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LanguageSelector" to register handlers implementing this interface.
 *
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
