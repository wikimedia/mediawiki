<?php

namespace MediaWiki\Skins\Hook;

use ResourceLoaderContext;

/**
 * @stable for implementation
 */
interface SkinPageReadyConfigHook {
	/**
	 * Allows skins to change the `mediawiki.page.ready` module configuration.
	 *
	 * @since 1.36
	 * @param ResourceLoaderContext $context
	 * @param mixed[] &$config Associative array of configurable options
	 * @return void This hook must not abort, it must return no value
	 */
	public function onSkinPageReadyConfig(
		ResourceLoaderContext $context,
		array &$config
	) : void;
}
