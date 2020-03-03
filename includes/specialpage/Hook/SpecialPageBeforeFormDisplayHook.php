<?php

namespace MediaWiki\SpecialPage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialPageBeforeFormDisplayHook {
	/**
	 * Before executing the HTMLForm object.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $name name of the special page
	 * @param ?mixed $form HTMLForm object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPageBeforeFormDisplay( $name, $form );
}
