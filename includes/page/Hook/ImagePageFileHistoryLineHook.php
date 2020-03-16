<?php

namespace MediaWiki\Page\Hook;

use File;
use ImagePage;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImagePageFileHistoryLineHook {
	/**
	 * This hook is called when a file history line is constructed.
	 *
	 * @since 1.35
	 *
	 * @param ImagePage $imagePage ImagePage object ($this)
	 * @param File $file
	 * @param string &$line HTML of the history line
	 * @param string &$css Line CSS class
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImagePageFileHistoryLine( $imagePage, $file, &$line, &$css );
}
