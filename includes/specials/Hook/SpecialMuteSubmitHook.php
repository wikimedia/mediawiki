<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialMuteSubmitHook {
	/**
	 * DEPRECATED since 1.34! Used only for instrumentation on SpecialMute
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $data Array containing information about submitted options on SpecialMute form
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialMuteSubmit( $data );
}
