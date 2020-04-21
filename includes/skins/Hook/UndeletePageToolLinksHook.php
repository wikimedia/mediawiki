<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UndeletePageToolLinksHook {
	/**
	 * Add one or more links to edit page subtitle when a page
	 * has been previously deleted.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context IContextSource (object)
	 * @param ?mixed $linkRenderer LinkRenderer instance
	 * @param ?mixed &$links Array of HTML strings
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUndeletePageToolLinks( $context, $linkRenderer, &$links );
}
