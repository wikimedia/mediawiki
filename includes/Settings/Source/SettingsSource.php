<?php

namespace MediaWiki\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;
use Stringable;

/**
 * A SettingsSource is meant to represent any kind of local or remote store
 * from which settings can be read, be it a local file, remote URL, database,
 * etc. It is concerned with reading (and possibly decoding) settings data.
 *
 * @since 1.38
 * @todo mark as stable before the 1.38 release
 */
interface SettingsSource extends Stringable {
	/**
	 * Loads and returns all settings from this source as an associative
	 * array.
	 *
	 * @return array
	 * @throws SettingsBuilderException
	 */
	public function load(): array;
}
