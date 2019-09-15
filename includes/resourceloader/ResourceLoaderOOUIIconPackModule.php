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
 * Allows loading arbitrary sets of OOUI icons.
 *
 * @ingroup ResourceLoader
 * @since 1.34
 */
class ResourceLoaderOOUIIconPackModule extends ResourceLoaderOOUIImageModule {
	public function __construct( $options = [], $localBasePath = null ) {
		parent::__construct( $options, $localBasePath );

		if ( !isset( $this->definition['icons'] ) || !$this->definition['icons'] ) {
			throw new InvalidArgumentException( "Parameter 'icons' must be given." );
		}

		// A few things check for the "icons" prefix on this value, so specify it even though
		// we don't use it for actually loading the data, like in the other modules.
		$this->definition['themeImages'] = 'icons';
	}

	private function getIcons() {
		return $this->definition['icons'];
	}

	protected function loadOOUIDefinition( $theme, $unused ) {
		// This is shared between instances of this class, so we only have to load the JSON files once
		static $data = [];

		if ( !isset( $data[$theme] ) ) {
			$data[$theme] = [];
			// Load and merge the JSON data for all "icons-foo" modules
			foreach ( self::$knownImagesModules as $module ) {
				if ( substr( $module, 0, 5 ) === 'icons' ) {
					$moreData = $this->readJSONFile( $this->getThemeImagesPath( $theme, $module ) );
					if ( $moreData ) {
						$data[$theme] = array_replace_recursive( $data[$theme], $moreData );
					}
				}
			}
		}

		$definition = $data[$theme];

		// Filter out the data for all other icons, leaving only the ones we want for this module
		$iconsNames = $this->getIcons();
		foreach ( array_keys( $definition['images'] ) as $iconName ) {
			if ( !in_array( $iconName, $iconsNames ) ) {
				unset( $definition['images'][$iconName] );
			}
		}

		return $definition;
	}

	public static function extractLocalBasePath( $options, $localBasePath = null ) {
		global $IP;
		if ( $localBasePath === null ) {
			$localBasePath = $IP;
		}
		// Ignore any 'localBasePath' present in $options, this always refers to files in MediaWiki core
		return $localBasePath;
	}
}
