<?php

namespace Wikimedia\DebugInfo;

/**
 * A trait for automatic __debugInfo() modifications.
 *
 * Recursion into properties is prevented if they have a @noVarDump annotation
 * in their doc comment. See T277618.
 *
 * @since 1.40
 */
trait DebugInfoTrait {
	public function __debugInfo() {
		return DumpUtils::objectToArray( $this );
	}
}
