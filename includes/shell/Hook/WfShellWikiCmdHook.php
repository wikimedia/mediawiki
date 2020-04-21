<?php

namespace MediaWiki\Shell\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WfShellWikiCmdHook {
	/**
	 * Called when generating a shell-escaped command line string to
	 * run a MediaWiki cli script.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$script MediaWiki cli script path
	 * @param ?mixed &$parameters Array of arguments and options to the script
	 * @param ?mixed &$options Associative array of options, may contain the 'php' and 'wrapper'
	 *   keys
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWfShellWikiCmd( &$script, &$parameters, &$options );
}
