<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleExistsHook {
	/**
	 * Called when determining whether a page exists at a given title.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title The title being tested.
	 * @param ?mixed &$exists Whether the title exists.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleExists( $title, &$exists );
}
