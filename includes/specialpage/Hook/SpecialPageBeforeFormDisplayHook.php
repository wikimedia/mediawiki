<?php

namespace MediaWiki\SpecialPage\Hook;

use MediaWiki\HTMLForm\HTMLForm;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialPageBeforeFormDisplay" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialPageBeforeFormDisplayHook {
	/**
	 * This hook is called before executing the HTMLForm object.
	 *
	 * @since 1.35
	 *
	 * @param string $name Name of the special page
	 * @param HTMLForm $form
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPageBeforeFormDisplay( $name, $form );
}
