<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetSlotDiffRendererHook {
	/**
	 * Replace or wrap the standard SlotDiffRenderer for some
	 * content type.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $contentHandler ContentHandler for which the slot diff renderer is fetched.
	 * @param ?mixed &$slotDiffRenderer SlotDiffRenderer to change or replace.
	 * @param ?mixed $context IContextSource
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetSlotDiffRenderer( $contentHandler, &$slotDiffRenderer,
		$context
	);
}
