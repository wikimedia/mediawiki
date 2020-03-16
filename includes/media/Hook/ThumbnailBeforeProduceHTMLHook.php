<?php

namespace MediaWiki\Hook;

use ThumbnailImage;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ThumbnailBeforeProduceHTMLHook {
	/**
	 * This hook is called before an image HTML is about to be
	 * rendered (by ThumbnailImage:toHtml method).
	 *
	 * @since 1.35
	 *
	 * @param ThumbnailImage $thumbnail
	 * @param array &$attribs Image attribute array
	 * @param array &$linkAttribs Image link attribute array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onThumbnailBeforeProduceHTML( $thumbnail, &$attribs,
		&$linkAttribs
	);
}
