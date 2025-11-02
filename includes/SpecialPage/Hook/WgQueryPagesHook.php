<?php

namespace MediaWiki\SpecialPage\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "wgQueryPages" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface WgQueryPagesHook {
	/**
	 * This hook is called when initialising list of QueryPage subclasses. Use this
	 * hook to add new query pages to be updated with maintenance/updateSpecialPages.php.
	 *
	 * @since 1.35
	 *
	 * @param array[] &$qp List of QueryPages
	 *  Format: [ class-string $class, string $specialPageName, ?int $limit (optional) ].
	 *  Limit defaults to $wgQueryCacheLimit if not given.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWgQueryPages( &$qp );
}
