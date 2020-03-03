<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialContributions__formatRow__flagsHook {
	/**
	 * Called before rendering a
	 * Special:Contributions row.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context IContextSource object
	 * @param ?mixed $row Revision information from the database
	 * @param ?mixed &$flags List of flags on this row
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialContributions__formatRow__flags( $context, $row,
		&$flags
	);
}
