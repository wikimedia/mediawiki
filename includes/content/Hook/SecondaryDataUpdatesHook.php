<?php

namespace MediaWiki\Content\Hook;

use Content;
use DataUpdate;
use ParserOutput;
use Title;

/**
 * @deprecated since 1.32 Use RevisionDataUpdates or override
 *   ContentHandler::getSecondaryDataUpdates instead.
 * @ingroup Hooks
 */
interface SecondaryDataUpdatesHook {
	/**
	 * Use this hook to modify the list of DataUpdates to perform when page content is modified.
	 *
	 * @deprecated since 1.32 Use RevisionDataUpdates or override
	 *   ContentHandler::getSecondaryDataUpdates instead.
	 *
	 * @param Title $title Title of the page that is being edited
	 * @param Content $oldContent Page content before the edit
	 * @param bool $recursive Whether DataUpdates should trigger recursive updates
	 *   (relevant mostly for LinksUpdate)
	 * @param ParserOutput $parserOutput Rendered version of the page after the edit
	 * @param DataUpdate[] &$updates List of DataUpdate objects, to be modified or replaced by
	 *   the hook handler
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSecondaryDataUpdates( $title, $oldContent, $recursive,
		$parserOutput, &$updates
	);
}
