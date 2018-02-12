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

		$iconName = $this->getIconName();
		if ( $iconName === '*' ) {
			return [];
		}

		// Filter out the data for all other icons, leaving only the one we want for this module
		$definition = $data[$theme];
		$definition['images'] = [ $iconName => $definition['images'][$iconName] ];
		return $definition;
	}

	// Mark this module as "missing" if it has no icons, to ease debugging of typos
	public function isMissing() {
		$this->loadFromDefinition();
		return !isset( $this->images['default'][ $this->getIconName() ] );
	}

	// Print deprecation information when loaded using mw.loader.load()
	public function getScript( ResourceLoaderContext $context ) {
		return $this->getDeprecationInformation();
	}

	public function getDeprecationInformation() {
		$this->loadFromDefinition();

		if ( isset( $this->images['default'][ $this->getIconName() ]['deprecated'] ) ) {
			$this->deprecated = $this->images['default'][ $this->getIconName() ]['deprecated'];
		}

		return parent::getDeprecationInformation();
	}
}
