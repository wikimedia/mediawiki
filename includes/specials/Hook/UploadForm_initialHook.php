<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UploadForm_initialHook {
	/**
	 * Before the upload form is generated. You might set the
	 * member-variables $uploadFormTextTop and $uploadFormTextAfterSummary to inject
	 * text (HTML) either before or after the editform.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $upload SpecialUpload object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadForm_initial( $upload );
}
