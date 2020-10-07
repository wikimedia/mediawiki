<?php

namespace MediaWiki\Installer\Hook;

use DatabaseUpdater;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LoadExtensionSchemaUpdatesHook {
	/**
	 * This hook is called during database installation and updates.
	 *
	 * @since 1.35
	 *
	 * @param DatabaseUpdater $updater DatabaseUpdater subclass
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLoadExtensionSchemaUpdates( $updater );
}
