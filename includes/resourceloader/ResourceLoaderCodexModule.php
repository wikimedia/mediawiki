<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Module for codex that has direction-specific style files and a static helper function for
 * embedding icons in package modules.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class ResourceLoaderCodexModule extends ResourceLoaderFileModule {

	protected $dirSpecificStyles = [];

	public function __construct( array $options = [], $localBasePath = null, $remoteBasePath = null ) {
		if ( isset( $options['dirSpecificStyles'] ) ) {
			$this->dirSpecificStyles = $options['dirSpecificStyles'];
		}

		parent::__construct( $options, $localBasePath, $remoteBasePath );
	}

	public function getStyleFiles( ResourceLoaderContext $context ) {
		// Add direction-specific styles
		$dir = $context->getDirection();
		if ( isset( $this->dirSpecificStyles[ $dir ] ) ) {
			$this->styles = array_merge( $this->styles, (array)$this->dirSpecificStyles[ $dir ] );
			// Empty dirSpecificStyles so we don't add them twice if getStyleFiles() is called twice
			$this->dirSpecificStyles = [];
		}

		return parent::getStyleFiles( $context );
	}

	/**
	 * Retrieve the specified icon definitions from codex-icons.json. Intended as a convenience
	 * function to be used in packageFiles definitions.
	 *
	 * Example:
	 *     "packageFiles": [
	 *         {
	 *             "name": "icons.json",
	 *             "callback": "ResourceLoaderCodexModule::getIcons",
	 *             "callbackParam": [
	 *                 "cdxIconClear",
	 *                 "cdxIconTrash"
	 *             ]
	 *         }
	 *     ]
	 *
	 * @param ResourceLoaderContext $context
	 * @param Config $config
	 * @param string[] $iconNames Names of icons to fetch
	 * @return array
	 */
	public static function getIcons( ResourceLoaderContext $context, Config $config, array $iconNames = [] ) {
		global $IP;
		static $allIcons = null;
		if ( $allIcons === null ) {
			$allIcons = json_decode( file_get_contents( "$IP/resources/lib/codex-icons/codex-icons.json" ), true );
		}
		return array_intersect_key( $allIcons, array_flip( $iconNames ) );
	}
}
