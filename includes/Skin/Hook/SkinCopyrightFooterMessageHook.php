<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;
use Wikimedia\Message\MessageSpecifier;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SkinCopyrightFooterMessage" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinCopyrightFooterMessageHook {
	/**
	 * Use this hook for site and per-namespace customization of the copyright notice as wikitext.
	 *
	 * @since 1.43
	 *
	 * @param Title $title Displayed page title
	 * @param string $type Set to 'normal' or 'history' for old/diff views
	 * @param MessageSpecifier &$msg Overridable message.
	 *   The default key is 'copyright-footer' or 'copyright-footer-history' for old/diff views,
	 *   with a link the license as the first parameter. The message will be displayed with parse().
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinCopyrightFooterMessage( $title, $type, &$msg );
}
