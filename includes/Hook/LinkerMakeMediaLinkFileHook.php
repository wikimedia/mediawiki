<?php

namespace MediaWiki\Hook;

use File;
use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LinkerMakeMediaLinkFile" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LinkerMakeMediaLinkFileHook {
	/**
	 * This hook is called at the end of Linker::makeMediaLinkFile() just before the return.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title that the link is pointing to
	 * @param File|bool $file File object or false if broken link
	 * @param string &$html Link text
	 * @param array &$attribs Attributes to be applied
	 * @param string &$ret Value to return if your hook returns false
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinkerMakeMediaLinkFile( $title, $file, &$html, &$attribs,
		&$ret
	);
}
