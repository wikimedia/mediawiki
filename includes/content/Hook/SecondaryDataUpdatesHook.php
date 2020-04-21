<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SecondaryDataUpdatesHook {
	/**
	 * DEPRECATED since 1.32! Use RevisionDataUpdates or override
	 * ContentHandler::getSecondaryDataUpdates instead.
	 * Allows modification of the list of DataUpdates to perform when page content is modified.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title of the page that is being edited.
	 * @param ?mixed $oldContent Content object representing the page's content before the edit.
	 * @param ?mixed $recursive bool indicating whether DataUpdates should trigger recursive
	 *   updates (relevant mostly for LinksUpdate).
	 * @param ?mixed $parserOutput ParserOutput representing the rendered version of the page
	 *   after the edit.
	 * @param ?mixed &$updates a list of DataUpdate objects, to be modified or replaced by
	 *   the hook handler.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSecondaryDataUpdates( $title, $oldContent, $recursive,
		$parserOutput, &$updates
	);
}
