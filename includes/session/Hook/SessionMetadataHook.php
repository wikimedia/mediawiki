<?php

namespace MediaWiki\Session\Hook;

use MediaWiki\Session\SessionBackend;
use WebRequest;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SessionMetadataHook {
	/**
	 * Use this hook to add metadata to a session being saved.
	 *
	 * @since 1.35
	 *
	 * @param SessionBackend $backend MediaWiki\Session\SessionBackend being saved
	 * @param array &$metadata Metadata to be stored. Add new keys here.
	 * @param WebRequest[] $requests Array of WebRequests potentially being
	 *   saved to. Generally 0-1 real request and 0+ FauxRequests.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSessionMetadata( $backend, &$metadata, $requests );
}
