<?php

namespace MediaWiki\Content\Hook;

use ContentHandler;
use IContextSource;
use SlotDiffRenderer;

/**
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
