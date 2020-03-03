<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LocalFile__getHistoryHook {
	/**
	 * Called before file history query performed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $file the File object
	 * @param ?mixed &$tables tables
	 * @param ?mixed &$fields select fields
	 * @param ?mixed &$conds conditions
	 * @param ?mixed &$opts query options
	 * @param ?mixed &$join_conds JOIN conditions
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalFile__getHistory( $file, &$tables, &$fields, &$conds,
		&$opts, &$join_conds
	);
}
