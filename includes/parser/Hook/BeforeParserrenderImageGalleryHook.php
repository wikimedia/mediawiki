<?php

namespace MediaWiki\Hook;

use ImageGalleryBase;
use Parser;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforeParserrenderImageGalleryHook {
	/**
	 * This hook is called before an image gallery is rendered by Parser.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param ImageGalleryBase $ig ImageGallery object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeParserrenderImageGallery( $parser, $ig );
}
