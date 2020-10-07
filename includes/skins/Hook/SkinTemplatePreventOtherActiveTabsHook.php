<?php

namespace MediaWiki\Hook;

use SkinTemplate;

/**
 * @deprecated since 1.35 Use SkinTemplateNavigation__Universal instead
 * @ingroup Hooks
 */
interface SkinTemplatePreventOtherActiveTabsHook {
	/**
	 * Use this hook to prevent showing active tabs.
	 *
	 * @since 1.35
	 *
	 * @param SkinTemplate $sktemplate
	 * @param bool &$res Set to true to prevent active tabs
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplatePreventOtherActiveTabs( $sktemplate, &$res );
}
