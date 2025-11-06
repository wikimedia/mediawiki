<?php

namespace MediaWiki\Hook;

use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialWhatLinksHereQueryHook" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialWhatLinksHereQueryHook {
	/**
	 * Use this hook to modify the query used by Special:WhatLinksHere.
	 * Implement the `SpecialPageBeforeFormDisplay` hook as well to add new
	 * elements to the form, which then contribute to the `$data` parameter
	 * of this hook.
	 *
	 * @since 1.43
	 *
	 * @param string $table table name
	 * @param array $data Data submitted from the form, as processed by `HTMLForm`
	 * @param SelectQueryBuilder $queryBuilder Query builder for altering the query being run
	 *
	 * @return void Un-abortable hook
	 */
	public function onSpecialWhatLinksHereQuery(
		$table,
		$data,
		$queryBuilder
	);
}
