<?php

namespace MediaWiki\Hook;

use IContextSource;
use stdClass;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialContributions__formatRow__flagsHook {
	/**
	 * This hook is called before rendering a Special:Contributions row.
	 *
	 * @since 1.35
	 *
	 * @param IContextSource $context IContextSource object
	 * @param stdClass $row Revision information from the database
	 * @param array &$flags List of flags on this row
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialContributions__formatRow__flags( $context, $row,
		&$flags
	);
}
