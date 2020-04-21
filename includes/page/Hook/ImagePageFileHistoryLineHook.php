<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImagePageFileHistoryLineHook {
	/**
	 * Called when a file history line is constructed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $imagePage ImagePage object ($this)
	 * @param ?mixed $file the file
	 * @param ?mixed &$line the HTML of the history line
	 * @param ?mixed &$css the line CSS class
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImagePageFileHistoryLine( $imagePage, $file, &$line, &$css );
}
