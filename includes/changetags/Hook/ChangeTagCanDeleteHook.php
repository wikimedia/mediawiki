<?php

namespace MediaWiki\ChangeTags\Hook;

use Status;
use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ChangeTagCanDelete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangeTagCanDeleteHook {
	/**
	 * Use this hook to tell whether a change tag should be able to be
	 * deleted from the UI (Special:Tags) or via the API. The default is that tags
	 * defined using the ListDefinedTags hook are not allowed to be deleted unless
	 * specifically allowed. Ensure you consume the ChangeTagAfterDelete hook to carry
	 * out custom deletion actions.
	 *
	 * @since 1.35
	 *
	 * @param string $tag Name of the tag
	 * @param User|null $user User initiating the action
	 * @param Status &$status To allow deletion of the tag, set `$status = Status::newGood()`,
	 *   and then return false from the hook function.
	 * @return bool|void True or no return value to continue or false to allow deletion of the tag
	 */
	public function onChangeTagCanDelete( $tag, $user, &$status );
}
