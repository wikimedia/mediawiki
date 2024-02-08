<?php

namespace MediaWiki\Hook;

use MediaWiki\Context\IContextSource;
use MediaWiki\Linker\LinkRenderer;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "HistoryPageToolLinks" to register handlers implementing this interface.
 *
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
	public function onHistoryPageToolLinks( IContextSource $context, LinkRenderer $linkRenderer, array &$links );
}
