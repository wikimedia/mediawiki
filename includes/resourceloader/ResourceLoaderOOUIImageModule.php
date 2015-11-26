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
			return;
		}

		// Core default themes
		$themes = array( 'default' => 'mediawiki' );
		$themes += ExtensionRegistry::getInstance()->getAttribute( 'SkinOOUIThemes' );

		$name = $this->definition['name'];
		$rootPath = $this->definition['rootPath'];

		$definition = array();
		foreach ( $themes as $skin => $theme ) {
			// TODO Allow extensions to specify this path somehow
			$dataPath = $this->localBasePath . '/' . $rootPath . '/' . $theme . '/' . $name . '.json';

			if ( file_exists( $dataPath ) ) {
				$data = json_decode( file_get_contents( $dataPath ), true );
				$fixPath = function ( &$path ) use ( $rootPath, $theme ) {
					// TODO Allow extensions to specify this path somehow
					$path = $rootPath . '/' . $theme . '/' . $path;
				};
				array_walk( $data['images'], function ( &$value ) use ( $fixPath ) {
					if ( is_string( $value['file'] ) ) {
						$fixPath( $value['file'] );
					} elseif ( is_array( $value['file'] ) ) {
						array_walk_recursive( $value['file'], $fixPath );
					}
				} );
			} else {
				$data = array();
			}

			foreach ( $data as $key => $value ) {
				switch ( $key ) {
					case 'images':
					case 'variants':
						$definition[$key][$skin] = $data[$key];
						break;

					default:
						if ( !isset( $definition[$key] ) ) {
							$definition[$key] = $data[$key];
						} elseif ( $definition[$key] !== $data[$key] ) {
							throw new Exception(
								"Mismatched OOUI theme definitions are not supported: trying to load $key of $theme theme"
							);
						}
						break;
				}
			}
		}

		// Fields from definition silently override keys from JSON files
		$this->definition += $definition;

		parent::loadFromDefinition();
	}
}
