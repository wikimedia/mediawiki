<?php

namespace MediaWiki\Gallery\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GalleryGetModes" to register handlers implementing this interface.
 *
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

/** @deprecated class alias since 1.46 */
class_alias( GalleryGetModesHook::class, 'MediaWiki\\Hook\\GalleryGetModesHook' );
