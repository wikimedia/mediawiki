<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GalleryGetModesHook {
	/**
	 * Use this hook to get a list of classes that can render different modes of a gallery.
	 *
	 * @since 1.35
	 *
	 * @param array &$modeArray Associative array mapping mode names to classes that implement
	 *   that mode. It is expected that all registered classes are a subclass of ImageGalleryBase.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGalleryGetModes( &$modeArray );
}
