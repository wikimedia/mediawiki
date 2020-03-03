<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MaintenanceRefreshLinksInitHook {
	/**
	 * before executing the refreshLinks.php maintenance
	 * script.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $refreshLinks RefreshLinks object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMaintenanceRefreshLinksInit( $refreshLinks );
}
