<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialMuteSubmit" to register handlers implementing this interface.
 *
 * @deprecated since 1.35, used only for instrumentation on Special:Mute
 * @ingroup Hooks
 */
interface SpecialMuteSubmitHook {
	/**
	 * This hook is called at the end of SpecialMute::onSubmit
	 *
	 * @since 1.35
	 *
	 * @param array $data Array containing information about submitted options on SpecialMute form
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialMuteSubmit( $data );
}
