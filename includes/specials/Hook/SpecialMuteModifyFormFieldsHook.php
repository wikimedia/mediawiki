<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialMuteModifyFormFieldsHook {
	/**
	 * Add more fields to Special:Mute
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sp SpecialPage object, for context
	 * @param ?mixed &$fields Current HTMLForm fields descriptors
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialMuteModifyFormFields( $sp, &$fields );
}
