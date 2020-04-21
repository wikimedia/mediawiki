<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface NamespaceIsMovableHook {
	/**
	 * Called when determining if it is possible to pages in a
	 * namespace.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $index Integer; the index of the namespace being checked.
	 * @param ?mixed &$result Boolean; whether MediaWiki currently thinks that pages in this
	 *   namespace are movable. Hooks may change this value to override the return
	 *   value of NamespaceInfo::isMovable().
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onNamespaceIsMovable( $index, &$result );
}
