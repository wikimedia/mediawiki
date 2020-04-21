<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GalleryGetModesHook {
	/**
	 * Get list of classes that can render different modes of a
	 * gallery.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$modeArray An associative array mapping mode names to classes that implement
	 *   that mode. It is expected all registered classes are a subclass of
	 *   ImageGalleryBase.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGalleryGetModes( &$modeArray );
}
