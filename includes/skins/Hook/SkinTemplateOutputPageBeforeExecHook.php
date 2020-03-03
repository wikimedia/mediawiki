<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinTemplateOutputPageBeforeExecHook {
	/**
	 * Before SkinTemplate::outputPage() starts
	 * page output.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sktemplate SkinTemplate object
	 * @param ?mixed $tpl QuickTemplate engine object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateOutputPageBeforeExec( $sktemplate, $tpl );
}
