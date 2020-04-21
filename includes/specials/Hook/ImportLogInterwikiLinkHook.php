<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImportLogInterwikiLinkHook {
	/**
	 * Hook to change the interwiki link used in log entries
	 * and edit summaries for transwiki imports.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$fullInterwikiPrefix Interwiki prefix, may contain colons.
	 * @param ?mixed &$pageTitle String that contains page title.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImportLogInterwikiLink( &$fullInterwikiPrefix, &$pageTitle );
}
