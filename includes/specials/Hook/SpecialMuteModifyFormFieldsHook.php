<?php

namespace MediaWiki\Hook;

use SpecialMute;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialMuteModifyFormFieldsHook {
	/**
	 * Use this hook to add more fields to Special:Mute
	 *
	 * @since 1.35
	 *
	 * @param SpecialMute $sp SpecialPage object, for context
	 * @param array &$fields Current HTMLForm fields descriptors
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialMuteModifyFormFields( $sp, &$fields );
}
