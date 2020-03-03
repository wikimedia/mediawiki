<?php

namespace MediaWiki\Languages\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LanguageGetTranslatedLanguageNamesHook {
	/**
	 * Provide translated language names.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$names array of language code => language name
	 * @param ?mixed $code language of the preferred translations
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLanguageGetTranslatedLanguageNames( &$names, $code );
}
