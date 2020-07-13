<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UploadFormSourceDescriptorsHook {
	/**
	 * This hook is called after the standard source inputs have been added to the descriptor.
	 *
	 * @since 1.35
	 *
	 * @param array &$descriptor The HTMLForm descriptor
	 * @param bool &$radio Boolean, if source type should be shown as radio button
	 * @param string $selectedSourceType The selected source type
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadFormSourceDescriptors( &$descriptor, &$radio,
		$selectedSourceType
	);
}
