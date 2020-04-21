<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UploadFormSourceDescriptorsHook {
	/**
	 * after the standard source inputs have been
	 * added to the descriptor
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$descriptor (array) the HTMLForm descriptor
	 * @param ?mixed &$radio Boolean, if source type should be shown as radio button
	 * @param ?mixed $selectedSourceType The selected source type
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadFormSourceDescriptors( &$descriptor, &$radio,
		$selectedSourceType
	);
}
