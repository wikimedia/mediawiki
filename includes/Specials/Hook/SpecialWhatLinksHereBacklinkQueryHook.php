<?php

namespace MediaWiki\Specials\Hook;

use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialWhatLinksHereBacklinkQuery" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialWhatLinksHereBacklinkQueryHook {
	/**
	 * Use this hook to filter the backlink query used by Special:WhatLinksHere.
	 * Implement the `SpecialPageBeforeFormDisplay` hook as well to add new
	 * elements to the form, which then contribute to `$data->formData`.
	 *
	 * This hook modifies the inner backlink query before it is embedded in the
	 * outer query, so that filtering is applied before pagination during query
	 * execution. Handlers should only add tables, joins, and conditions to the
	 * query builder.
	 *
	 * For the redirect table, there is no outer query and `page` is already
	 * joined with the alias `page`. For pagelinks, templatelinks, and imagelinks,
	 * the inner query does not join `page`. Handlers that need `page` should
	 * add the join only for these tables, joining `$data->sourcePageIdField`
	 * to `page_id`, and reuse the existing join for redirects.
	 *
	 * @since 1.48
	 *
	 * @param SpecialWhatLinksHereBacklinkQueryData $data Backlink query context
	 * @param SelectQueryBuilder $queryBuilder Query builder for altering the query being run
	 *
	 * @return void Un-abortable hook
	 */
	public function onSpecialWhatLinksHereBacklinkQuery(
		SpecialWhatLinksHereBacklinkQueryData $data,
		SelectQueryBuilder $queryBuilder
	): void;
}
