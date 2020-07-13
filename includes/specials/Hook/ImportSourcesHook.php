<?php

namespace MediaWiki\Hook;

/**
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
	 * @param array &$importSources The value of $wgImportSources. Modify as necessary. See the
	 *   comment in DefaultSettings.php for the detail of how to structure this array.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImportSources( &$importSources );
}
