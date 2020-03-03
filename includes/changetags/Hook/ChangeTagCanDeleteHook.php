<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangeTagCanDeleteHook {
	/**
	 * Tell whether a change tag should be able to be
	 * deleted from the UI (Special:Tags) or via the API. The default is that tags
	 * defined using the ListDefinedTags hook are not allowed to be deleted unless
	 * specifically allowed. If you wish to allow deletion of the tag, set
	 * `$status = Status::newGood()` to allow deletion, and then `return false` from
	 * the hook function. Ensure you consume the 'ChangeTagAfterDelete' hook to carry
	 * out custom deletion actions.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $tag name of the tag
	 * @param ?mixed $user user initiating the action
	 * @param ?mixed &$status Status object. See above.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeTagCanDelete( $tag, $user, &$status );
}
