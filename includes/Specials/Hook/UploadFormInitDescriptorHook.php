<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UploadFormInitDescriptor" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UploadFormInitDescriptorHook {
	/**
	 * This hook is called after the descriptor for the upload form as been assembled.
	 *
	 * @since 1.35
	 *
	 * @param array &$descriptor (array) the HTMLForm descriptor
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadFormInitDescriptor( &$descriptor );
}
