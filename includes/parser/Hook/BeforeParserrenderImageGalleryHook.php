<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforeParserrenderImageGalleryHook {
	/**
	 * Before an image gallery is rendered by Parser.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object
	 * @param ?mixed $ig ImageGallery object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeParserrenderImageGallery( $parser, $ig );
}
