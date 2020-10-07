<?php

namespace MediaWiki\Page\Hook;

use File;
use ImageHistoryList;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImagePageFileHistoryLineHook {
	/**
	 * This hook is called when a file history line is constructed.
	 *
	 * @since 1.35
	 *
	 * @param ImageHistoryList $imageHistoryList Formerly an ImagePage but since
	 *   1.27 it is an ImageHistoryList.
	 * @param File $file
	 * @param string &$line HTML of the history line
	 * @param string &$css Line CSS class
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImagePageFileHistoryLine( $imageHistoryList, $file, &$line, &$css );
}
