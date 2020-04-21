<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinTemplatePreventOtherActiveTabsHook {
	/**
	 * Use this to prevent showing active tabs.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sktemplate SkinTemplate object
	 * @param ?mixed &$res set to true to prevent active tabs
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplatePreventOtherActiveTabs( $sktemplate, &$res );
}
