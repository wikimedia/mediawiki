<?php

namespace MediaWiki\SpecialPage\Hook;

use MediaWiki\SpecialPage\ChangesListSpecialPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ChangesListSpecialPageStructuredFilters" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangesListSpecialPageStructuredFiltersHook {
	/**
	 * Use this hook to register filters for pages inheriting from ChangesListSpecialPage
	 * (in core: RecentChanges, RecentChangesLinked, and Watchlist). Generally, you will
	 * want to construct new ChangesListBooleanFilter or ChangesListStringOptionsFilter objects.
	 * When constructing them, you specify which group they belong to. You can reuse
	 * existing groups (accessed through $special->getFilterGroup), or create your own
	 * (ChangesListBooleanFilterGroup or ChangesListStringOptionsFilterGroup).
	 * If you create new groups, you must register them with $special->registerFilterGroup.
	 * Note that this is called regardless of whether the user is currently using
	 * the new (structured) or old (unstructured) filter UI. If you want your boolean
	 * filter to show on both the new and old UI, specify all the supported fields.
	 * These include showHide, label, and description.
	 * See the constructor of each ChangesList* class for documentation of supported
	 * fields.
	 *
	 * @since 1.35
	 *
	 * @param ChangesListSpecialPage $special
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListSpecialPageStructuredFilters( $special );
}
