<?php

namespace MediaWiki\Hook;

use Config;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UploadForm_getInitialPageTextHook {
	/**
	 * This hook is called after the initial page text for file uploads is generated.
	 *
	 * It allows extensions to modify the initial page text.
	 *
	 * @since 1.35
	 *
	 * @param string &$pageText The page text
	 * @param string[] $msg Array of header messages
	 * @param Config $config
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadForm_getInitialPageText( &$pageText, $msg, $config );
}
