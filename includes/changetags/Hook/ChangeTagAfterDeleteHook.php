<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangeTagAfterDeleteHook {
	/**
	 * Called after a change tag has been deleted (that is,
	 * removed from all revisions and log entries to which it was applied). This gives
	 * extensions a chance to take it off their books.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $tag name of the tag
	 * @param ?mixed &$status Status object. Add warnings to this as required. There is no point
	 *   setting errors, as the deletion has already been partly carried out by this
	 *   point.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeTagAfterDelete( $tag, &$status );
}
