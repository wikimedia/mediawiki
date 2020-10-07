<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImportLogInterwikiLinkHook {
	/**
	 * Use this hook to change interwiki links in log entries and edit summaries for transwiki imports
	 *
	 * @since 1.35
	 *
	 * @param string &$fullInterwikiPrefix Interwiki prefix, may contain colons.
	 * @param string &$pageTitle String that contains page title.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImportLogInterwikiLink( &$fullInterwikiPrefix, &$pageTitle );
}
