<?php

namespace MediaWiki\Hook;

use QuickTemplate;
use SkinTemplate;

/**
 * @deprecated since 1.35. See
 * https://www.mediawiki.org/wiki/Manual:Hooks/SkinTemplateOutputPageBeforeExec
 * for migration notes.
 * @see https://phabricator.wikimedia.org/T60137 for details on the deprecation
 *
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
