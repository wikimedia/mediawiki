<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ThumbnailBeforeProduceHTMLHook {
	/**
	 * Called before an image HTML is about to be
	 * rendered (by ThumbnailImage:toHtml method).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $thumbnail the ThumbnailImage object
	 * @param ?mixed &$attribs image attribute array
	 * @param ?mixed &$linkAttribs image link attribute array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onThumbnailBeforeProduceHTML( $thumbnail, &$attribs,
		&$linkAttribs
	);
}
