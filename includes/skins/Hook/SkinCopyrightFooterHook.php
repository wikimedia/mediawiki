<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinCopyrightFooterHook {
	/**
	 * Allow for site and per-namespace customization of
	 * copyright notice.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title displayed page title
	 * @param ?mixed $type 'normal' or 'history' for old/diff views
	 * @param ?mixed &$msg overridable message; usually 'copyright' or 'history_copyright'. This
	 *   message must be in HTML format, not wikitext!
	 * @param ?mixed &$link overridable HTML link to be passed into the message as $1
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinCopyrightFooter( $title, $type, &$msg, &$link );
}
