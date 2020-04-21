<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OutputPageBodyAttributesHook {
	/**
	 * Called when OutputPage::headElement is creating the
	 * body tag to allow for extensions to add attributes to the body of the page they
	 * might need. Or to allow building extensions to add body classes that aren't of
	 * high enough demand to be included in core.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $out The OutputPage which called the hook, can be used to get the real title
	 * @param ?mixed $sk The Skin that called OutputPage::headElement
	 * @param ?mixed &$bodyAttrs An array of attributes for the body tag passed to Html::openElement
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOutputPageBodyAttributes( $out, $sk, &$bodyAttrs );
}
