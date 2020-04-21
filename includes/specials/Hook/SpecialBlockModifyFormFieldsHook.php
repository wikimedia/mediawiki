<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialBlockModifyFormFieldsHook {
	/**
	 * Add more fields to Special:Block
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sp SpecialPage object, for context
	 * @param ?mixed &$fields Current HTMLForm fields
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialBlockModifyFormFields( $sp, &$fields );
}
