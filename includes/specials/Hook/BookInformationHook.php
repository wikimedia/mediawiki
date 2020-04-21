<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BookInformationHook {
	/**
	 * Before information output on Special:Booksources.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $isbn ISBN to show information for
	 * @param ?mixed $output OutputPage object in use
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBookInformation( $isbn, $output );
}
