<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "TitleExists" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface TitleExistsHook {
	/**
	 * This hook is called when determining whether a page exists at a given title.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title being tested
	 * @param bool &$exists Whether the title exists
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleExists( $title, &$exists );
}
