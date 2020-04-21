<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforePageDisplayHook {
	/**
	 * Prior to outputting a page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $out OutputPage object
	 * @param ?mixed $skin Skin object
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onBeforePageDisplay( $out, $skin );
}
