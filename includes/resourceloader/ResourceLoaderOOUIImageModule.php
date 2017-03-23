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
 * Secret special sauce.
 *
 * @since 1.26
 */
class ResourceLoaderOOUIImageModule extends ResourceLoaderImageModule {
	protected function loadFromDefinition() {
		if ( $this->definition === null ) {
			// Do nothing if definition was already processed
			return;
		}

		// Core default themes
		$themes = [ 'default' => 'mediawiki' ];
		$themes += ExtensionRegistry::getInstance()->getAttribute( 'SkinOOUIThemes' );

		$name = $this->definition['name'];
		$rootPath = $this->definition['rootPath'];

		$definition = [];
		foreach ( $themes as $skin => $theme ) {
			// Find the path to the JSON file which contains the actual image definitions for this theme
			// TODO Allow extensions to specify this path somehow
			$dataPath = $rootPath . '/' . strtolower( $theme ) . '/' . $name . '.json';
			$localDataPath = $this->localBasePath . '/' . $dataPath;

			// If there's no file for this module of this theme, that's okay, it will just use the defaults
			if ( !file_exists( $localDataPath ) ) {
				continue;
			}
			$data = json_decode( file_get_contents( $localDataPath ), true );

			// Expand the paths to images (since they are relative to the JSON file that defines them, not
			// our base directory)
			$fixPath = function ( &$path ) use ( $dataPath ) {
				$path = dirname( $dataPath ) . '/' . $path;
			};
			array_walk( $data['images'], function ( &$value ) use ( $fixPath ) {
				if ( is_string( $value['file'] ) ) {
					$fixPath( $value['file'] );
				} elseif ( is_array( $value['file'] ) ) {
					array_walk_recursive( $value['file'], $fixPath );
				}
			} );

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
									"key '$key' of theme '$theme' " .
									"does not match other themes"
							);
						}
						break;
				}
			}
		}

		// Fields from module definition silently override keys from JSON files
		$this->definition += $definition;

		parent::loadFromDefinition();
	}
}
