<?php

namespace MediaWiki\Hook;

use OutputPage;
use Skin;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforePageDisplayHook {
	/**
	 * This hook is called prior to outputting a page.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return void This hook must not abort, it must return no value
	 */
	public function onBeforePageDisplay( $out, $skin ) : void;
}
