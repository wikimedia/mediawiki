<?php

namespace MediaWiki\Hook;

use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialPrefixIndexQuery" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialPrefixIndexQueryHook {
	/**
	 * Use this hook to modify the query used by Special:PrefixIndex.
	 *
	 * @since 1.42
	 *
	 * @param array $fieldData
	 * @param SelectQueryBuilder $queryBuilder Query builder for altering the query being run
	 *
	 * @return void Un-abortable hook
	 */
	public function onSpecialPrefixIndexQuery( array $fieldData, SelectQueryBuilder $queryBuilder );
}
