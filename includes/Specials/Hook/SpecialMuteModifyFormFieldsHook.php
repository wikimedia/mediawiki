<?php

namespace MediaWiki\Specials\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialMuteModifyFormFields" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialMuteModifyFormFieldsHook {
	/**
	 * Use this hook to add more fields to Special:Mute
	 *
	 * @since 1.35
	 *
	 * @param User|null $target Target user
	 * @param User $user Context user
	 * @param array &$fields Current HTMLForm fields descriptors
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialMuteModifyFormFields( $target, $user, &$fields );
}

/** @deprecated class alias since 1.46 */
class_alias( SpecialMuteModifyFormFieldsHook::class, 'MediaWiki\\Hook\\SpecialMuteModifyFormFieldsHook' );
