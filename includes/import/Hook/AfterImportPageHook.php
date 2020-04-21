<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AfterImportPageHook {
	/**
	 * When a page import is completed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title under which the revisions were imported
	 * @param ?mixed $foreignTitle ForeignTitle object based on data provided by the XML file
	 * @param ?mixed $revCount Number of revisions in the XML file
	 * @param ?mixed $sRevCount Number of successfully imported revisions
	 * @param ?mixed $pageInfo associative array of page information
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAfterImportPage( $title, $foreignTitle, $revCount,
		$sRevCount, $pageInfo
	);
}
