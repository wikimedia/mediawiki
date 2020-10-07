<?php

namespace MediaWiki\Shell\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface WfShellWikiCmdHook {
	/**
	 * This hook is called when generating a shell-escaped command line string to
	 * run a MediaWiki CLI script.
	 *
	 * @since 1.35
	 *
	 * @param string &$script MediaWiki CLI script path
	 * @param string[] &$parameters Array of arguments and options to the script
	 * @param array &$options Associative array of options, may contain the 'php' and 'wrapper'
	 *   keys
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWfShellWikiCmd( &$script, &$parameters, &$options );
}
