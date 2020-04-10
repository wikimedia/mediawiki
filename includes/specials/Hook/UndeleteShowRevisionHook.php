<?php

namespace MediaWiki\Hook;

use Revision;
use Title;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UndeleteShowRevisionHook {
	/**
	 * This hook is called when showing a revision in Special:Undelete.
	 *
	 * @since 1.35
	 *
	 * @param Title $title title object related to the revision
	 * @param Revision $rev revision (object) that will be viewed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUndeleteShowRevision( $title, $rev );
}
