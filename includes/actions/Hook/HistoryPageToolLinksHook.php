<?php

namespace MediaWiki\Hook;

use IContextSource;
use MediaWiki\Linker\LinkRenderer;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface HistoryPageToolLinksHook {
	/**
	 * Use this hook to add one or more links to revision history page subtitle.
	 *
	 * @since 1.35
	 *
	 * @param IContextSource $context
	 * @param LinkRenderer $linkRenderer
	 * @param string[] &$links Array of HTML strings
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHistoryPageToolLinks( $context, $linkRenderer, &$links );
}
