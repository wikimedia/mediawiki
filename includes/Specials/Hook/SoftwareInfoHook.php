<?php

namespace MediaWiki\Specials\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SoftwareInfo" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SoftwareInfoHook {
	/**
	 * This hook is called by Special:Version for returning information about the software.
	 *
	 * @since 1.35
	 *
	 * @param array &$software The array of software in format 'name' => 'version'. See
	 *   SpecialVersion::softwareInformation().
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSoftwareInfo( &$software );
}

/** @deprecated class alias since 1.46 */
class_alias( SoftwareInfoHook::class, 'MediaWiki\\Hook\\SoftwareInfoHook' );
