<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;
use stdClass;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "WhatLinksHereProps" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface WhatLinksHerePropsHook {
	/**
	 * Use this hook to add annotations to Special:WhatLinksHere.
	 *
	 * @since 1.35
	 *
	 * @param stdClass $row The DB row of the entry.
	 * @param Title $title The Title of the page where the link comes FROM
	 * @param Title $target The Title of the page where the link goes TO
	 * @param string[] &$props Array of HTML strings to display after the title.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWhatLinksHereProps( $row, $title, $target, &$props );
}
