<?php

namespace MediaWiki\Hook;

use MediaWiki\Specials\SpecialUpload;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UploadForm:BeforeProcessing" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UploadForm_BeforeProcessingHook {
	/**
	 * This hook is called at the beginning of processUpload().
	 *
	 * Lets you poke at member variables like $mUploadDescription before the file is saved.
	 * Do not use this hook to break upload processing. This will return the user to a blank
	 * form with no error message; use UploadVerifyUpload or UploadVerifyFile instead.
	 *
	 * @since 1.35
	 *
	 * @param SpecialUpload $upload
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadForm_BeforeProcessing( $upload );
}
