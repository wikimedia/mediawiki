<?php

namespace MediaWiki\Hook;

use QuickTemplate;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface SkinTemplateToolboxEndHook {
	/**
	 * This hook is called by SkinTemplate skins after toolbox links have
	 * been rendered (useful for adding more).
	 *
	 * @since 1.35
	 *
	 * @param QuickTemplate $sk QuickTemplate based skin template running the hook
	 * @param bool $dummy Called when SkinTemplateToolboxEnd is used from a BaseTemplate skin,
	 *   extensions that add support for BaseTemplateToolbox should watch for this
	 *   dummy parameter with "$dummy=false" in their code and return without echoing
	 *   any HTML to avoid creating duplicate toolbox items.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateToolboxEnd( $sk, $dummy );
}
