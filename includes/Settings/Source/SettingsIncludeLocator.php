<?php

namespace MediaWiki\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;

/**
 * Implementations of SettingsSource may additionally implement SettingsIncludeLocator
 * as well, to provide support for relative include locations. For instance, a
 * SettingsSource that loads a file may provide support for includes to be
 * specified relative to the location of that file.
 *
 * @since 1.38
 * @stable to implement
 */
interface SettingsIncludeLocator {

	/**
	 * This method defines how a relative reference to the location of
	 * another settings source is interpreted.
	 *
	 * It tries to make $location absolute by interpreting it as
	 * relative to the location of the SettingsSource it originates from.
	 *
	 * Implementation are "best effort". If a location cannot be made
	 * absolute, it may be returned as-is. Implementations are also free
	 * to throw a SettingsBuilderException to indicate that the given
	 * include location is not supported in this context.
	 *
	 * @param string $location
	 *
	 * @return string
	 * @throws SettingsBuilderException if the given location cannot be used
	 *         as an include by the current source.
	 */
	public function locateInclude( string $location ): string;
}
