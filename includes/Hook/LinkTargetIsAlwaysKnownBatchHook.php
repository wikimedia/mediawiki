<?php

namespace MediaWiki\Hook;

use MediaWiki\Linker\LinkTarget;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LinkTargetIsAlwaysKnownBatch" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LinkTargetIsAlwaysKnownBatchHook {

	/**
	 * This hook is called when determining if a page exist in batched. Use this hook to
	 * override default behaviour for determining if a page exists.
	 *
	 * If the value for a link is left as null in $isKnown, regular checks happen.
	 * If it's a boolean, this value is returned by the isAlwaysKnown method.
	 *
	 * @param LinkTarget[] $links
	 * @param (bool|null)[] &$isAlwaysKnown Evaluation decision for each link (same order as
	 * links in $links)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinkTargetIsAlwaysKnownBatch( array $links, array &$isAlwaysKnown );
}
