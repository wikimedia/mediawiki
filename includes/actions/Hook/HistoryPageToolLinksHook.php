<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface HistoryPageToolLinksHook {
	/**
	 * Add one or more links to revision history page subtitle.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context IContextSource (object)
	 * @param ?mixed $linkRenderer LinkRenderer instance
	 * @param ?mixed &$links Array of HTML strings
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHistoryPageToolLinks( $context, $linkRenderer, &$links );
}
