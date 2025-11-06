<?php

namespace MediaWiki\Installer\Hook;

use MediaWiki\Installer\DatabaseUpdater;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LoadExtensionSchemaUpdates" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LoadExtensionSchemaUpdatesHook {
	/**
	 * This hook is called during database installation and updates.
	 *
	 * Do not use this hook with a handler that uses a "services" or "optional_services"
	 * option in its ObjectFactory spec. It is called in a context where the global
	 * service locator is not initialised.
	 *
	 * @since 1.35
	 *
	 * @param DatabaseUpdater $updater DatabaseUpdater subclass
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLoadExtensionSchemaUpdates( $updater );
}
