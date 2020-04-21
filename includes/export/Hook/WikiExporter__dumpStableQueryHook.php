<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WikiExporter__dumpStableQueryHook {
	/**
	 * Get the SELECT query for "stable" revisions
	 * dumps. One, and only one hook should set this, and return false.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tables Database tables to use in the SELECT query
	 * @param ?mixed &$opts Options to use for the query
	 * @param ?mixed &$join Join conditions
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWikiExporter__dumpStableQuery( &$tables, &$opts, &$join );
}
