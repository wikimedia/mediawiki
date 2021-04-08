<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "NamespaceIsMovable" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface NamespaceIsMovableHook {
	/**
	 * This hook is called when determining if it is possible to move pages in a
	 * namespace.
	 *
	 * @since 1.35
	 *
	 * @param int $index Index of the namespace being checked
	 * @param bool &$result Whether MediaWiki currently thinks that pages in this
	 *   namespace are movable. Hooks may change this value to override the return
	 *   value of NamespaceInfo::isMovable().
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onNamespaceIsMovable( $index, &$result );
}
