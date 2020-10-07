<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface TitleIsAlwaysKnownHook {
	/**
	 * This hook is called when determining if a page exists. Use this hook to
	 * override default behavior for determining if a page exists. If $isKnown is
	 * kept as null, regular checks happen. If it's a boolean, this value is returned
	 * by the isKnown method.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object that is being checked
	 * @param bool|null &$isKnown Whether MediaWiki currently thinks this page is known
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleIsAlwaysKnown( $title, &$isKnown );
}
