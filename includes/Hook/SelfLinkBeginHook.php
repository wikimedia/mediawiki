<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SelfLinkBeginHook {
	/**
	 * Called before a link to the current article is displayed to
	 * allow the display of the link to be customized.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $nt the Title object
	 * @param ?mixed &$html html to display for the link
	 * @param ?mixed &$trail optional text to display before $html
	 * @param ?mixed &$prefix optional text to display after $html
	 * @param ?mixed &$ret the value to return if your hook returns false
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSelfLinkBegin( $nt, &$html, &$trail, &$prefix, &$ret );
}
