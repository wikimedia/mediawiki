<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UploadForm_getInitialPageTextHook {
	/**
	 * After the initial page text for file uploads
	 * is generated, to allow it to be altered.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$pageText the page text
	 * @param ?mixed $msg array of header messages
	 * @param ?mixed $config Config object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadForm_getInitialPageText( &$pageText, $msg, $config );
}
