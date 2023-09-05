<?php

namespace MediaWiki\Hook;

use MediaWiki\Output\OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BookInformation" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface BookInformationHook {
	/**
	 * This hook is called before information output on Special:Booksources.
	 *
	 * @since 1.35
	 *
	 * @param string $isbn ISBN to show information for
	 * @param OutputPage $output OutputPage object in use
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBookInformation( $isbn, $output );
}
