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
 * Allows loading arbitrary single OOUI icons.
 *
 * @since 1.32
 */
class ResourceLoaderOOUISingleIconModule extends ResourceLoaderOOUIImageModule {
	private function getIconName() {
		$nameParts = explode( '.', $this->getName() );
		return array_pop( $nameParts );
	}

	protected function loadOOUIDefinition( $theme, $unused ) {
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

		return $data[$theme];
	}

	public function getImages( ResourceLoaderContext $context ) {
		$images = parent::getImages( $context );
		$iconName = $this->getIconName();

		if ( !isset( $images[$iconName] ) ) {
			return [];
		}

		// Filter out the data for all other icons, leaving only the one we want
		return [ $iconName => $images[$iconName] ];
	}

	public function isMissing() {
		$this->loadFromDefinition();
		return !isset( $this->images['default'][ $this->getIconName() ] );
	}

	// Override 'protected' to 'public'
	public function getDeprecationInformation() {
		$this->loadFromDefinition();

		if ( isset( $this->images['default'][ $this->getIconName() ]['deprecated'] ) ) {
			$this->deprecated = $this->images['default'][ $this->getIconName() ]['deprecated'];
		}

		return parent::getDeprecationInformation();
	}
}
