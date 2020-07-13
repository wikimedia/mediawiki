<?php

namespace MediaWiki\ChangeTags\Hook;

use Status;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangeTagCanCreateHook {
	/**
	 * Use this hook to tell whether a change tag should be able to be created
	 * from the UI (Special:Tags) or via the API. You could use this hook if you want
	 * to reserve a specific "namespace" of tags, or something similar.
	 *
	 * @since 1.35
	 *
	 * @param string $tag Name of the tag
	 * @param User $user User initiating the action
	 * @param Status &$status Add your errors using `$status->fatal()` or warnings
	 *   using `$status->warning()`. Errors and warnings will be relayed to the user.
	 *   If you set an error, the user will be unable to create the tag.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeTagCanCreate( $tag, $user, &$status );
}
