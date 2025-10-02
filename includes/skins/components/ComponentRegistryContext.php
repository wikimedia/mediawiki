<?php
/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Skin;

use MediaWiki\Config\Config;
use MediaWiki\Title\Title;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
interface ComponentRegistryContext {
	/**
	 * Returns the config needed for the component.
	 *
	 * @return Config
	 */
	public function getConfig(): Config;

	/**
	 * Returns the Title object for the component.
	 *
	 * @return Title
	 */
	public function getTitle(): Title;
}
