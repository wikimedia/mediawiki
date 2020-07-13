<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use File;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LocalFile__getHistoryHook {
	/**
	 * This hook is called before a file history query is performed.
	 *
	 * @since 1.35
	 *
	 * @param File $file
	 * @param array &$tables Tables
	 * @param array &$fields Select fields
	 * @param array &$conds Conditions
	 * @param array &$opts Query options
	 * @param array &$join_conds JOIN conditions
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalFile__getHistory( $file, &$tables, &$fields, &$conds,
		&$opts, &$join_conds
	);
}
