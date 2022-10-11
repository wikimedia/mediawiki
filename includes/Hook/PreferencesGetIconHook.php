<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PreferencesGetIcon" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PreferencesGetIconHook {
	/**
	 * Use the hook to add an icon that will be displayed in the mobile layout of
	 * Special:Preferences.
	 *
	 * @since 1.40
	 * @param array &$iconNames a key-value array that assigns an icon name to a section name.
	 * The key is the section name, the value is the icon name.
	 * You can obtain the icon names from
	 * https://doc.wikimedia.org/oojs-ui/master/demos/?page=icons&theme=wikimediaui&direction=ltr&platform=desktop
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPreferencesGetIcon( &$iconNames );
}
