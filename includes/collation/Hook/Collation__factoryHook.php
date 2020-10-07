<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use Collation;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface Collation__factoryHook {
	/**
	 * This hook is called if $wgCategoryCollation is an unknown collation.
	 *
	 * @since 1.35
	 *
	 * @param string $collationName Name of the collation in question
	 * @param Collation|null &$collationObject Null. Replace with a subclass of
	 *   the Collation class that implements the collation given in $collationName.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCollation__factory( $collationName, &$collationObject );
}
