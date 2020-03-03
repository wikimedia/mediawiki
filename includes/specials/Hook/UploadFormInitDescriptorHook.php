<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UploadFormInitDescriptorHook {
	/**
	 * After the descriptor for the upload form as been
	 * assembled.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$descriptor (array) the HTMLForm descriptor
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadFormInitDescriptor( &$descriptor );
}
