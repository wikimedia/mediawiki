<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetMetadataVersion" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetMetadataVersionHook {
	/**
	 * Use this hook to modify the image metadata version currently in use. This
	 * is used when requesting image metadata from a ForeignApiRepo. Media handlers
	 * that need to have versioned metadata should add an element to the end of the
	 * version array of the form 'handler_name=version'. Most media handlers won't need
	 * to do this unless they broke backwards compatibility with a previous version of
	 * the media handler metadata output.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$version Array of version strings
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetMetadataVersion( &$version );
}
