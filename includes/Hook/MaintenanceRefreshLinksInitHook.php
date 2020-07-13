<?php

namespace MediaWiki\Hook;

use RefreshLinks;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MaintenanceRefreshLinksInitHook {
	/**
	 * This hook is called before executing the refreshLinks.php maintenance script.
	 *
	 * @since 1.35
	 *
	 * @param RefreshLinks $refreshLinks
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMaintenanceRefreshLinksInit( $refreshLinks );
}
