<?php

namespace MediaWiki\Hook;

use OutputPage;
use Skin;

/**
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
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOutputPageBodyAttributes( $out, $sk, &$bodyAttrs );
}
