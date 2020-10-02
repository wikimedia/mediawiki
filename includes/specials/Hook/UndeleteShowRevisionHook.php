<?php

namespace MediaWiki\Hook;

use Revision;
use Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UndeleteShowRevision" to register handlers implementing this interface.
 *
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface UndeleteShowRevisionHook {
	/**
	 * This hook is called when showing a revision in Special:Undelete.
	 *
	 * @since 1.35
	 *
	 * @param Title $title The title of the revision
	 * @param Revision $rev The revision that will be viewed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUndeleteShowRevision( $title, $rev );
}
