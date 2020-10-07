<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GitViewersHook {
	/**
	 * This hook is called when generating the list of git viewers for Special:Version,
	 * allowing you to modify the list.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$extTypes Associative array of repo URLS to viewer URLs
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGitViewers( &$extTypes );
}
