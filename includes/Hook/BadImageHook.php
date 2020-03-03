<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BadImageHook {
	/**
	 * When checking against the bad image list. Change $bad and return
	 * false to override. If an image is "bad", it is not rendered inline in wiki
	 * pages or galleries in category pages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $name Image name being checked
	 * @param ?mixed &$bad Whether or not the image is "bad"
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBadImage( $name, &$bad );
}
