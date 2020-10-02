<?php

namespace MediaWiki\Hook;

use ImageGalleryBase;
use Parser;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BeforeParserrenderImageGallery" to register handlers implementing this interface.
 *
 * @deprecated since 1.35
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
