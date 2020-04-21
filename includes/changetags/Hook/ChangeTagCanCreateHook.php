<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangeTagCanCreateHook {
	/**
	 * Tell whether a change tag should be able to be created
	 * from the UI (Special:Tags) or via the API. You could use this hook if you want
	 * to reserve a specific "namespace" of tags, or something similar.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $tag name of the tag
	 * @param ?mixed $user user initiating the action
	 * @param ?mixed &$status Status object. Add your errors using `$status->fatal()` or warnings
	 *   using `$status->warning()`. Errors and warnings will be relayed to the user.
	 *   If you set an error, the user will be unable to create the tag.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeTagCanCreate( $tag, $user, &$status );
}
