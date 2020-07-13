<?php

namespace MediaWiki\Hook;

use SpecialUpload;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UploadForm_initialHook {
	/**
	 * This hook is called before the upload form is generated.
	 *
	 * Extensions might set the member-variables $uploadFormTextTop and
	 * $uploadFormTextAfterSummary to inject text (HTML) either before or after the editform.
	 *
	 * @since 1.35
	 *
	 * @param SpecialUpload $upload
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadForm_initial( $upload );
}
