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
 * Loads the module definition from JSON files in the format that OOUI uses, converting it to the
 * format we use. (Previously known as secret special sauce.)
 *
 * @since 1.26
 */
class ResourceLoaderOOUIImageModule extends ResourceLoaderImageModule {
	use ResourceLoaderOOUIModule;

	protected function loadFromDefinition() {
		if ( $this->definition === null ) {
			// Do nothing if definition was already processed
			return;
		}

		$themes = self::getSkinThemeMap();

		// For backwards-compatibility, allow missing 'themeImages'
		$module = $this->definition['themeImages'] ?? '';

		$definition = [];
		foreach ( $themes as $skin => $theme ) {
			$data = $this->loadOOUIDefinition( $theme, $module );

			if ( !$data ) {
				// If there's no file for this module of this theme, that's okay, it will just use the defaults
				continue;
			}

			// Convert into a definition compatible with the parent vanilla ResourceLoaderImageModule
			foreach ( $data as $key => $value ) {
				switch ( $key ) {
					// Images and color variants are defined per-theme, here converted to per-skin
					case 'images':
					case 'variants':
						$definition[$key][$skin] = $data[$key];
						break;

					// Other options must be identical for each theme (or only defined in the default one)
					default:
						if ( !isset( $definition[$key] ) ) {
							$definition[$key] = $data[$key];
						} elseif ( $definition[$key] !== $data[$key] ) {
							throw new Exception(
								"Mismatched OOUI theme images definition: " .
									"key '$key' of theme '$theme' for module '$module' " .
									"does not match other themes"
							);
						}
						break;
				}
			}
		}

		// Extra selectors to allow using the same icons for old-style MediaWiki UI code
		if ( substr( $module, 0, 5 ) === 'icons' ) {
			$definition['selectorWithoutVariant'] = '.oo-ui-icon-{name}, .mw-ui-icon-{name}:before';
			$definition['selectorWithVariant'] = '.oo-ui-image-{variant}.oo-ui-icon-{name}, ' .
				'.mw-ui-icon-{name}-{variant}:before';
		}

		// Fields from module definition silently override keys from JSON files
		$this->definition += $definition;

		parent::loadFromDefinition();
	}

	/**
	 * Load the module definition from the JSON file(s) for the given theme and module.
	 *
	 * @since 1.34
	 * @param string $theme
	 * @param string $module
	 * @return array
	 */
	protected function loadOOUIDefinition( $theme, $module ) {
		// Find the path to the JSON file which contains the actual image definitions for this theme
		if ( $module ) {
			$dataPath = $this->getThemeImagesPath( $theme, $module );
			if ( !$dataPath ) {
				return [];
			}
		} else {
			// Backwards-compatibility for things that probably shouldn't have used this class...
			$dataPath =
				$this->definition['rootPath'] . '/' .
				strtolower( $theme ) . '/' .
				$this->definition['name'] . '.json';
		}

		return $this->readJSONFile( $dataPath );
	}

	/**
	 * Read JSON from a file, and transform all paths in it to be relative to the module's base path.
	 *
	 * @since 1.34
	 * @param string $dataPath Path relative to the module's base bath
	 * @return array|false
	 */
	protected function readJSONFile( $dataPath ) {
		$localDataPath = $this->getLocalPath( $dataPath );

		if ( !file_exists( $localDataPath ) ) {
			return false;
		}

		$data = json_decode( file_get_contents( $localDataPath ), true );

		// Expand the paths to images (since they are relative to the JSON file that defines them, not
		// our base directory)
		$fixPath = function ( &$path ) use ( $dataPath ) {
			if ( $dataPath instanceof ResourceLoaderFilePath ) {
				$path = new ResourceLoaderFilePath(
					dirname( $dataPath->getPath() ) . '/' . $path,
					$dataPath->getLocalBasePath(),
					$dataPath->getRemoteBasePath()
				);
			} else {
				$path = dirname( $dataPath ) . '/' . $path;
			}
		};
		array_walk( $data['images'], function ( &$value ) use ( $fixPath ) {
			if ( is_string( $value['file'] ) ) {
				$fixPath( $value['file'] );
			} elseif ( is_array( $value['file'] ) ) {
				array_walk_recursive( $value['file'], $fixPath );
			}
		} );

		return $data;
	}
}
