<?php

namespace MediaWiki\Hook;

use Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "TitleGetRestrictionTypes" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface TitleGetRestrictionTypesHook {
	/**
	 * Use this hook to modify the types of protection
	 * that can be applied.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title in question
	 * @param string[] &$types Types of protection available
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleGetRestrictionTypes( $title, &$types );
}
