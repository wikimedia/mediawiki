<?php

namespace MediaWiki\Installer\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LoadExtensionSchemaUpdatesHook {
	/**
	 * Called during database installation and updates.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $updater A DatabaseUpdater subclass
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLoadExtensionSchemaUpdates( $updater );
}
