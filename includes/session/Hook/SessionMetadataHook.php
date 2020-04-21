<?php

namespace MediaWiki\Session\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SessionMetadataHook {
	/**
	 * Add metadata to a session being saved.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $backend MediaWiki\Session\SessionBackend being saved.
	 * @param ?mixed &$metadata Array Metadata to be stored. Add new keys here.
	 * @param ?mixed $requests Array of WebRequests potentially being saved to. Generally 0-1 real
	 *   request and 0+ FauxRequests.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSessionMetadata( $backend, &$metadata, $requests );
}
