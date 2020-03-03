<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleIsAlwaysKnownHook {
	/**
	 * Called when determining if a page exists. Allows
	 * overriding default behavior for determining if a page exists. If $isKnown is
	 * kept as null, regular checks happen. If it's a boolean, this value is returned
	 * by the isKnown method.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object that is being checked
	 * @param ?mixed &$isKnown Boolean|null; whether MediaWiki currently thinks this page is known
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleIsAlwaysKnown( $title, &$isKnown );
}
