<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetLangPreferredVariantHook {
	/**
	 * Called in LanguageConverter#getPreferredVariant() to
	 *   allow fetching the language variant code from cookies or other such
	 *   alternative storage.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$req language variant from the URL (string) or boolean false if no variant
	 *   was specified in the URL; the value of this variable comes from
	 *   LanguageConverter#getURLVariant()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLangPreferredVariant( &$req );
}
