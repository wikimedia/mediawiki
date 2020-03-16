<?php

namespace MediaWiki\Hook;

use QuickTemplate;
use SkinTemplate;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinTemplateOutputPageBeforeExecHook {
	/**
	 * This hook is called before SkinTemplate::outputPage() starts
	 * page output.
	 *
	 * @since 1.35
	 *
	 * @param SkinTemplate $sktemplate
	 * @param QuickTemplate $tpl QuickTemplate engine object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateOutputPageBeforeExec( $sktemplate, $tpl );
}
