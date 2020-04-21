<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LinksUpdateConstructedHook {
	/**
	 * At the end of LinksUpdate() is construction.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $linksUpdate the LinksUpdate object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinksUpdateConstructed( $linksUpdate );
}
