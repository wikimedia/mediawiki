<?php

namespace MediaWiki\Specials\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ImportLogInterwikiLink" to register handlers implementing this interface.
 *
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

/** @deprecated class alias since 1.46 */
class_alias( ImportLogInterwikiLinkHook::class, 'MediaWiki\\Hook\\ImportLogInterwikiLinkHook' );
