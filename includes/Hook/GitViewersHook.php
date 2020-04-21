<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GitViewersHook {
	/**
	 * Called when generating the list of git viewers for
	 * Special:Version, use this to change the list.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$extTypes associative array of repo URLS to viewer URLs.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGitViewers( &$extTypes );
}
