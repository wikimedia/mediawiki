<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UndeleteShowRevisionHook {
	/**
	 * Called when showing a revision in Special:Undelete.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title title object related to the revision
	 * @param ?mixed $rev revision (object) that will be viewed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUndeleteShowRevision( $title, $rev );
}
