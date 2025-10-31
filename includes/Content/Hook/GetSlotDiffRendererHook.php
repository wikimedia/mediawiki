<?php

namespace MediaWiki\Content\Hook;

use MediaWiki\Content\ContentHandler;
use MediaWiki\Context\IContextSource;
use SlotDiffRenderer;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetSlotDiffRenderer" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetSlotDiffRendererHook {
	/**
	 * Use this hook to replace or wrap the standard SlotDiffRenderer for some content type.
	 *
	 * @since 1.35
	 *
	 * @param ContentHandler $contentHandler ContentHandler for which the slot diff renderer is fetched
	 * @param SlotDiffRenderer &$slotDiffRenderer SlotDiffRenderer to change or replace
	 * @param IContextSource $context
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetSlotDiffRenderer( $contentHandler, &$slotDiffRenderer,
		$context
	);
}
