<?php

namespace MediaWiki\Session\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SessionCheckInfoHook {
	/**
	 * Validate a MediaWiki\Session\SessionInfo as it's being
	 * loaded from storage. Return false to prevent it from being used.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$reason String rejection reason to be logged
	 * @param ?mixed $info MediaWiki\Session\SessionInfo being validated
	 * @param ?mixed $request WebRequest being loaded from
	 * @param ?mixed $metadata Array|false Metadata array for the MediaWiki\Session\Session
	 * @param ?mixed $data Array|false Data array for the MediaWiki\Session\Session
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSessionCheckInfo( &$reason, $info, $request, $metadata,
		$data
	);
}
