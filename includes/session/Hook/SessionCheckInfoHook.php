<?php

namespace MediaWiki\Session\Hook;

use MediaWiki\Session\SessionInfo;
use WebRequest;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SessionCheckInfoHook {
	/**
	 * Use this hook to validate a MediaWiki\Session\SessionInfo as it's being
	 * loaded from storage.
	 *
	 * @since 1.35
	 *
	 * @param string &$reason Rejection reason to be logged
	 * @param SessionInfo $info MediaWiki\Session\SessionInfo being validated
	 * @param WebRequest $request WebRequest being loaded from
	 * @param array|bool $metadata Metadata array for the MediaWiki\Session\Session
	 * @param array|bool $data Data array for the MediaWiki\Session\Session
	 * @return bool|void True or no return value to continue, or false to to prevent
	 *   the MediaWiki\Session\SessionInfo from being used
	 */
	public function onSessionCheckInfo( &$reason, $info, $request, $metadata,
		$data
	);
}
