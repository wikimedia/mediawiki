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
 * Backwards-compatibility hack to load all icons belonging to an icon pack.
 *
 * @deprecated since 1.32 Do not use except for modules like 'oojs-ui.styles.icons-xxx'
 * @since 1.32
 */
class ResourceLoaderOOUIIconPackModule extends ResourceLoaderOOUIImageModule {
	// Suppress any style output and don't do any related work. We only subclass
	// ResourceLoaderOOUIImageModule to use loadFromDefinition() in other methods.
	public function getImages( ResourceLoaderContext $context ) {
		return [];
	}

	// Print deprecation info when loading from JS code (using mw.loader.load() etc.).
	public function getScript( ResourceLoaderContext $context = null ) {
		return $this->getDeprecationInformation();
	}

	// Override 'protected' to 'public' (called from OutputPage::addModuleStyles()).
	public function getDeprecationInformation() {
		$this->loadFromDefinition();
		$dependencies = $this->getDependencies();

		if ( $dependencies ) {
			$this->deprecated = "Use individual icon modules like '$dependencies[0]' instead.";
		}

		return parent::getDeprecationInformation();
	}

	// These are not real "dependencies", but reusing this mechanism allows this to work
	// when loading from JS code (using mw.loader.load() etc.). We also resolve them in
	// OutputPage::addModuleStyles() (this is why this is 'public').
	public function getDependencies( ResourceLoaderContext $context = null ) {
		$this->loadFromDefinition();

		$module = $this->getName();
		if ( strpos( $module, 'oojs-ui.styles.icons-' ) === 0 ) {
			$images = array_keys( $this->images['default'] );
			return array_map( function ( $image ) {
				return "oojs-ui.styles.icon.$image";
			}, $images );
		}

		return parent::getDependencies();
	}
}
