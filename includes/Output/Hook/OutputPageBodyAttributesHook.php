<?php

namespace MediaWiki\Output\Hook;

use MediaWiki\Output\OutputPage;
use MediaWiki\Skin\Skin;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "OutputPageBodyAttributes" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface OutputPageBodyAttributesHook {
	/**
	 * Add classes or other attributes to the body element.
	 *
	 * The body classes and attributes MUST NOT vary by user, due to ESI constraints (T439272).
	 * Add user-specific classes to the html element via OutputPage::addHtmlClasses instead,
	 * for example from a BeforePageDisplayHook.
	 *
	 * This hook is called from OutputPage::headElement.
	 *
	 * @since 1.35
	 * @param OutputPage $out OutputPage
	 * @param Skin $sk Skin
	 * @param string[] &$bodyAttrs Array of attributes for the body tag passed to Html::openElement
	 * @return void This hook must not abort, it must return no value
	 */
	public function onOutputPageBodyAttributes( $out, $sk, &$bodyAttrs ): void;
}

/** @deprecated class alias since 1.42 */
class_alias( OutputPageBodyAttributesHook::class, 'MediaWiki\Hook\OutputPageBodyAttributesHook' );
