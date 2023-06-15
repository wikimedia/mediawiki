<?php

namespace MediaWiki\Diff\Hook;

use IContextSource;
use TextSlotDiffRenderer;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "TextSlotDiffRendererTablePrefix" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface TextSlotDiffRendererTablePrefixHook {

	/**
	 * Use this hook to change the HTML that is included in a prefix container directly before the diff table.
	 *
	 * @since 1.41
	 *
	 * @param TextSlotDiffRenderer $textSlotDiffRenderer
	 * @param IContextSource $context
	 * @param (string|null)[] &$parts HTML strings to add to a container above the diff table.
	 * Will be sorted by key before being output.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTextSlotDiffRendererTablePrefix(
		TextSlotDiffRenderer $textSlotDiffRenderer,
		IContextSource $context,
		array &$parts
	);
}
