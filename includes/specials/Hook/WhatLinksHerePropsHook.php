<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WhatLinksHerePropsHook {
	/**
	 * Allows annotations to be added to WhatLinksHere
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $row The DB row of the entry.
	 * @param ?mixed $title The Title of the page where the link comes FROM
	 * @param ?mixed $target The Title of the page where the link goes TO
	 * @param ?mixed &$props Array of HTML strings to display after the title.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWhatLinksHereProps( $row, $title, $target, &$props );
}
