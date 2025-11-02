<?php

namespace MediaWiki\Language\Hook;

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

/** @deprecated class alias since 1.45 */
class_alias( LanguageGetTranslatedLanguageNamesHook::class,
	'MediaWiki\\Languages\\Hook\\LanguageGetTranslatedLanguageNamesHook' );
