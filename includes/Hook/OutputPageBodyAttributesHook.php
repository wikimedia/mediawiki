<?php

namespace MediaWiki\Hook;

use MediaWiki\Output\OutputPage;
use Skin;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "OutputPageBodyAttributes" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface OutputPageBodyAttributesHook {
	/**
	 * This hook is called when OutputPage::headElement is creating the
	 * body tag to allow for extensions to add attributes to the body of the page they
	 * might need. Or to allow building extensions to add body classes that aren't of
	 * high enough demand to be included in core.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $out OutputPage which called the hook, can be used to get the real title
	 * @param Skin $sk Skin that called OutputPage::headElement
	 * @param string[] &$bodyAttrs Array of attributes for the body tag passed to Html::openElement
	 * @return void This hook must not abort, it must return no value
	 */
	public function onOutputPageBodyAttributes( $out, $sk, &$bodyAttrs ): void;
}
