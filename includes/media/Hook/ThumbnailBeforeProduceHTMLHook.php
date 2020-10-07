<?php

namespace MediaWiki\Hook;

use ThumbnailImage;

/**
 * @stable to implement
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
	 * @param string[] &$attribs Image attribute array
	 * @param string[] &$linkAttribs Image link attribute array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onThumbnailBeforeProduceHTML( $thumbnail, &$attribs,
		&$linkAttribs
	);
}
