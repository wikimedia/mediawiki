<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ImportSources" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ImportSourcesHook {
	/**
	 * This hook is called when reading from the $wgImportSources configuration variable.
	 *
	 * This can be used to lazy-load the import sources list.
	 *
	 * @since 1.35
	 *
	 * @param array &$importSources The value of $wgImportSources. Modify as necessary.
	 *   See \MediaWiki\MainConfigSchema::ImportSources.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImportSources( &$importSources );
}
