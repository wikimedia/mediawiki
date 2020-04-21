<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinTemplateToolboxEndHook {
	/**
	 * Called by SkinTemplate skins after toolbox links have
	 * been rendered (useful for adding more).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sk The QuickTemplate based skin template running the hook.
	 * @param ?mixed $dummy Called when SkinTemplateToolboxEnd is used from a BaseTemplate skin,
	 *   extensions that add support for BaseTemplateToolbox should watch for this
	 *   dummy parameter with "$dummy=false" in their code and return without echoing
	 *   any HTML to avoid creating duplicate toolbox items.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateToolboxEnd( $sk, $dummy );
}
