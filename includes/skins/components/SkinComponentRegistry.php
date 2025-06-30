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
 */

namespace MediaWiki\Skin;

use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\SpecialPage\SpecialPage;
use RuntimeException;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
class SkinComponentRegistry {
	/** @var SkinComponent[]|null null if not initialized. */
	private $components = null;

	/** @var SkinComponentRegistryContext */
	private $skinContext;

	public function __construct( SkinComponentRegistryContext $skinContext ) {
		$this->skinContext = $skinContext;
	}

	/**
	 * Get a component. This method has side effects in that
	 * if registered components have been not initialized they
	 * will be registered as part of this method.
	 *
	 * @param string $name
	 * @throws RuntimeException with unknown name
	 * @return SkinComponent
	 */
	public function getComponent( string $name ): SkinComponent {
		if ( $this->components === null ) {
			$this->registerComponents();
		}
		$component = $this->components[$name] ?? null;
		if ( !$component ) {
			throw new RuntimeException( 'Unknown component: ' . $name );
		}
		return $component;
	}

	/**
	 * Return all registered components.
	 *
	 * @since 1.38
	 * @return SkinComponent[]
	 */
	public function getComponents() {
		if ( $this->components === null ) {
			$this->registerComponents();
		}
		return $this->components;
	}

	/**
	 * Registers a component for use with the skin.
	 * Private for now, but in future we may consider making this a
	 * public method to allow skins to extend component definitions.
	 *
	 * @param string $name
	 * @throws RuntimeException if given an unknown name
	 */
	private function registerComponent( string $name ) {
		$skin = $this->skinContext;
		switch ( $name ) {
			case 'copyright':
				$component = new SkinComponentCopyright(
					$skin
				);
				break;
			case 'logos':
				$component = new SkinComponentLogo(
					$skin->getConfig(),
					$skin->getLanguage()
				);
				break;
			case 'search-box':
				$component = new SkinComponentSearch(
					$skin->getConfig(),
					$skin->getMessageLocalizer(),
					SpecialPage::newSearchPage( $skin->getUser() )
				);
				break;
			case 'toc':
				$component = new SkinComponentTableOfContents( $skin->getOutput() );
				break;
			case 'last-modified':
				$component = new SkinComponentLastModified(
					$skin,
					$skin->getOutput()->getRevisionTimestamp()
				);
				break;
			case 'rendered-with':
				$component = new SkinComponentRenderedWith(
					$skin,
					$skin->getOutput()->getOutputFlag( ParserOutputFlags::USE_PARSOID )
				);
				break;
			case 'footer':
				$component = new SkinComponentFooter( $skin );
				break;
			default:
				throw new RuntimeException( 'Unknown component: ' . $name );
		}
		$this->components[$name] = $component;
	}

	/**
	 * Registers components used by skin.
	 */
	private function registerComponents() {
		$this->registerComponent( 'copyright' );
		$this->registerComponent( 'last-modified' );
		$this->registerComponent( 'logos' );
		$this->registerComponent( 'toc' );
		$this->registerComponent( 'search-box' );
		$this->registerComponent( 'footer' );
	}
}
