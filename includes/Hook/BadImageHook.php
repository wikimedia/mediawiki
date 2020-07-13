<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BadImageHook {
	/**
	 * This hook is called when checking against the bad image list. If an image is "bad",
	 * it is not rendered inline in wiki pages or galleries in category pages.
	 *
	 * @since 1.35
	 *
	 * @param string $name Image name being checked
	 * @param bool &$bad Whether or not the image is "bad"
	 * @return bool|void True or no return value to continue, or false and change $bad
	 *   to override
	 */
	public function onBadImage( $name, &$bad );
}
