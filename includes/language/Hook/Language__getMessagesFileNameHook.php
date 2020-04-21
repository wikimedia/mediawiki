<?php

namespace MediaWiki\Languages\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface Language__getMessagesFileNameHook {
	/**
	 * @since 1.35
	 *
	 * @param ?mixed $code The language code or the language we're looking for a messages file for
	 * @param ?mixed &$file The messages file path, you can override this to change the location.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLanguage__getMessagesFileName( $code, &$file );
}
