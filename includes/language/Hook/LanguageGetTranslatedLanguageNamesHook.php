<?php

namespace MediaWiki\Languages\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LanguageGetTranslatedLanguageNames" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LanguageGetTranslatedLanguageNamesHook {
	/**
	 * Use this hook to provide translated language names.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$names Array of language code => language name
	 * @param string $code Language of the preferred translations
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLanguageGetTranslatedLanguageNames( &$names, $code );
}
