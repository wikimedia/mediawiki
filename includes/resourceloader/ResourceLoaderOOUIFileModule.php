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
			$options['skinScripts'] = $this->getSkinSpecific( $options[ 'themeScripts' ], 'scripts' );
		}
		if ( isset( $options[ 'themeStyles' ] ) ) {
			$options['skinStyles'] = $this->getSkinSpecific( $options[ 'themeStyles' ], 'styles' );
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
