<?php

namespace MediaWiki\SpecialPage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WgQueryPagesHook {
	/**
	 * Called when initialising list of QueryPage subclasses, use this
	 * to add new query pages to be updated with maintenance/updateSpecialPages.php.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$qp The list of QueryPages
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWgQueryPages( &$qp );
}
