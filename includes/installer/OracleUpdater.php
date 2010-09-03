<?php
/**
 * Oracle-specific updater.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for handling updates to Oracle databases.
 * 
 * @ingroup Deployment
 * @since 1.17
 */
class OracleUpdater extends DatabaseUpdater {
	protected function getCoreUpdateList() {
		return array();
	}
}
