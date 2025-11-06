<?php

namespace MediaWiki\Skins\Hook;

use MediaWiki\ResourceLoader as RL;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SkinPageReadyConfig" to register handlers implementing this interface.
 *
 * @stable to implement
 */
interface SkinPageReadyConfigHook {
	/**
	 * Allows skins to change the `mediawiki.page.ready` module configuration.
	 *
	 * @since 1.36
	 * @param RL\Context $context
	 * @param mixed[] &$config Associative array of configurable options
	 * @return void This hook must not abort, it must return no value
	 */
	public function onSkinPageReadyConfig(
		RL\Context $context,
		array &$config
	);
}
