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
 * ResourceLoaderFileModule which magically loads the right skinScripts and skinStyles for every
 * skin, using the specified OOUI theme for each.
 *
 * @since 1.30
 */
class ResourceLoaderOOUIFileModule extends ResourceLoaderFileModule {
	use ResourceLoaderOOUIModule;

	public function __construct( $options = [] ) {
		if ( isset( $options[ 'themeScripts' ] ) ) {
			$skinScripts = $this->getSkinSpecific( $options[ 'themeScripts' ], 'scripts' );

			if ( !isset( $options['skinScripts'] ) ) {
				$options['skinScripts'] = [];
			}
			// For each skin where skinScripts are defined, add our ones at the beginning
			foreach ( $options['skinScripts'] as $skin => $files ) {
				if ( !is_array( $files ) ) {
					$files = [ $files ];
				}
				if ( isset( $skinScripts[$skin] ) ) {
					$options['skinScripts'][$skin] = array_merge( [ $skinScripts[$skin] ], $files );
				} elseif ( isset( $skinScripts['default'] ) ) {
					$options['skinScripts'][$skin] = array_merge( [ $skinScripts['default'] ], $files );
				}
			}
			// Add our remaining skinScripts for skins that did not have them defined
			foreach ( $skinScripts as $skin => $file ) {
				if ( !isset( $options['skinScripts'][$skin] ) ) {
					$options['skinScripts'][$skin] = $file;
				}
			}
		}
		if ( isset( $options[ 'themeStyles' ] ) ) {
			$skinStyles = $this->getSkinSpecific( $options[ 'themeStyles' ], 'styles' );

			if ( !isset( $options['skinStyles'] ) ) {
				$options['skinStyles'] = [];
			}
			// For each skin where skinStyles are defined, add our ones at the beginning
			foreach ( $options['skinStyles'] as $skin => $files ) {
				if ( !is_array( $files ) ) {
					$files = [ $files ];
				}
				if ( isset( $skinStyles[$skin] ) ) {
					$options['skinStyles'][$skin] = array_merge( [ $skinStyles[$skin] ], $files );
				} elseif ( isset( $skinStyles['default'] ) ) {
					$options['skinStyles'][$skin] = array_merge( [ $skinStyles['default'] ], $files );
				}
			}
			// Add our remaining skinStyles for skins that did not have them defined
			foreach ( $skinStyles as $skin => $file ) {
				if ( !isset( $options['skinStyles'][$skin] ) ) {
					$options['skinStyles'][$skin] = $file;
				}
			}
		}

		parent::__construct( $options );
	}

	/**
	 * Helper function to generate values for 'skinStyles' and 'skinScripts'.
	 *
	 * @param string $module Module to generate skinStyles/skinScripts for:
	 *   'core', 'widgets', 'toolbars', 'windows'
	 * @param string $which 'scripts' or 'styles'
	 * @return array
	 */
	private function getSkinSpecific( $module, $which ) {
		$themes = self::getSkinThemeMap();

		return array_combine(
			array_keys( $themes ),
			array_map( function ( $theme ) use ( $module, $which ) {
				if ( $which === 'scripts' ) {
					return $this->getThemeScriptsPath( $theme, $module );
				} else {
					return $this->getThemeStylesPath( $theme, $module );
				}
			}, array_values( $themes ) )
		);
	}
}
