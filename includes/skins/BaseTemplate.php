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

use Wikimedia\WrappedString;
use Wikimedia\WrappedStringList;

/**
 * New base template for a skin's template extended from QuickTemplate
 * this class features helper methods that provide common ways of interacting
 * with the data stored in the QuickTemplate
 *
 * @stable to extend
 */
abstract class BaseTemplate extends QuickTemplate {

	/**
	 * Get a Message object with its context set
	 *
	 * @param string $name Message name
	 * @param mixed ...$params Message params
	 * @return Message
	 */
	public function getMsg( $name, ...$params ) {
		return $this->getSkin()->msg( $name, ...$params );
	}

	public function msg( $str ) {
		echo $this->getMsg( $str )->escaped();
	}

	/**
	 * Create an array of common toolbox items from the data in the quicktemplate
	 * stored by SkinTemplate and items added by hook to the 'toolbox' section.
	 * The resulting array is built according to a format intended to be passed
	 * through makeListItem to generate the html.
	 *
	 * @deprecated since 1.35. To add items to the toolbox, use SidebarBeforeOutput
	 * hook. To get the toolbox only use $this->data['sidebar']['TOOLBOX'], if you are
	 * extending this class.
	 * @return array
	 */
	public function getToolbox() {
		$toolbox = $this->getSkin()->makeToolbox(
			$this->data['nav_urls'],
			$this->data['feeds']
		);

		// Merge content that might be added to the toolbox section by hook
		if ( isset( $this->data['sidebar']['TOOLBOX'] ) ) {
			$toolbox = array_merge( $toolbox, $this->data['sidebar']['TOOLBOX'] ?? [] );
		}

		// T253416: Deprecated hook
		$this->getHookRunner()->onBaseTemplateToolbox( $this, $toolbox );
		return $toolbox;
	}

	/**
	 * @deprecated since 1.35 use Skin::getPersonalToolsForMakeListItem
	 * @return array
	 */
	public function getPersonalTools() {
		return $this->getSkin()->getPersonalToolsForMakeListItem( $this->get( 'personal_urls' ) );
	}

	/**
	 * @param array $options (optional) allows disabling certain sidebar elements.
	 *  The keys `search`, `toolbox` and `languages` are accepted.
	 * @return array representing the sidebar
	 */
	protected function getSidebar( $options = [] ) {
		// Force the rendering of the following portals
		$sidebar = $this->data['sidebar'];
		if ( !isset( $sidebar['SEARCH'] ) ) {
			$sidebar['SEARCH'] = true;
		}
		if ( !isset( $sidebar['TOOLBOX'] ) ) {
			$sidebar['TOOLBOX'] = true;
		}
		if ( !isset( $sidebar['LANGUAGES'] ) ) {
			$sidebar['LANGUAGES'] = true;
		}

		if ( !isset( $options['search'] ) || $options['search'] !== true ) {
			unset( $sidebar['SEARCH'] );
		}
		if ( isset( $options['toolbox'] ) && $options['toolbox'] === false ) {
			unset( $sidebar['TOOLBOX'] );
		}
		if ( isset( $options['languages'] ) && $options['languages'] === false ) {
			unset( $sidebar['LANGUAGES'] );
		}

		$boxes = [];
		foreach ( $sidebar as $boxName => $content ) {
			if ( $content === false ) {
				continue;
			}
			switch ( $boxName ) {
				case 'SEARCH':
					// Search is a special case, skins should custom implement this
					$boxes[$boxName] = [
						'id' => 'p-search',
						'header' => $this->getMsg( 'search' )->text(),
						'generated' => false,
						'content' => true,
					];
					break;
				case 'TOOLBOX':
					$msgObj = $this->getMsg( 'toolbox' );
					$boxes[$boxName] = [
						'id' => 'p-tb',
						'header' => $msgObj->exists() ? $msgObj->text() : 'toolbox',
						'generated' => false,
						'content' => $this->getToolbox(),
					];
					break;
				case 'LANGUAGES':
					if ( $this->data['language_urls'] !== false ) {
						$msgObj = $this->getMsg( 'otherlanguages' );
						$boxes[$boxName] = [
							'id' => 'p-lang',
							'header' => $msgObj->exists() ? $msgObj->text() : 'otherlanguages',
							'generated' => false,
							'content' => $this->data['language_urls'] ?: [],
						];
					}
					break;
				default:
					$msgObj = $this->getMsg( $boxName );
					$boxes[$boxName] = [
						'id' => "p-$boxName",
						'header' => $msgObj->exists() ? $msgObj->text() : $boxName,
						'generated' => true,
						'content' => $content,
					];
					break;
			}
		}

		// HACK: Compatibility with extensions still using SkinTemplateToolboxEnd
		$hookContents = null;
		if ( isset( $boxes['TOOLBOX'] ) ) {
			ob_start();
			// We pass an extra 'true' at the end so extensions using BaseTemplateToolbox
			// can abort and avoid outputting double toolbox links
			$this->getHookRunner()->onSkinTemplateToolboxEnd( $this, true );
			$hookContents = ob_get_contents();
			ob_end_clean();
			if ( !trim( $hookContents ) ) {
				$hookContents = null;
			}
		}
		// END hack

		if ( isset( $options['htmlOnly'] ) && $options['htmlOnly'] === true ) {
			foreach ( $boxes as $boxName => $box ) {
				if ( is_array( $box['content'] ) ) {
					$content = '<ul>';
					foreach ( $box['content'] as $key => $val ) {
						$content .= "\n	" . $this->getSkin()->makeListItem( $key, $val );
					}
					// HACK, shove the toolbox end onto the toolbox if we're rendering itself
					if ( $hookContents ) {
						$content .= "\n	$hookContents";
					}
					// END hack
					$content .= "\n</ul>\n";
					$boxes[$boxName]['content'] = $content;
				}
			}
		} elseif ( $hookContents ) {
			$boxes['TOOLBOXEND'] = [
				'id' => 'p-toolboxend',
				'header' => $boxes['TOOLBOX']['header'],
				'generated' => false,
				'content' => "<ul>{$hookContents}</ul>",
			];
			// HACK: Make sure that TOOLBOXEND is sorted next to TOOLBOX
			$boxes2 = [];
			foreach ( $boxes as $key => $box ) {
				if ( $key === 'TOOLBOXEND' ) {
					continue;
				}
				$boxes2[$key] = $box;
				if ( $key === 'TOOLBOX' ) {
					$boxes2['TOOLBOXEND'] = $boxes['TOOLBOXEND'];
				}
			}
			$boxes = $boxes2;
			// END hack
		}

		return $boxes;
	}

	/**
	 * @deprecated since 1.35 use Skin::getAfterPortlet directly
	 * @param string $name
	 */
	protected function renderAfterPortlet( $name ) {
		echo $this->getAfterPortlet( $name );
	}

	/**
	 * Allows extensions to hook into known portlets and add stuff to them
	 *
	 * @deprecated since 1.35 use Skin::getAfterPortlet directly
	 *
	 * @param string $name
	 *
	 * @return string html
	 * @since 1.29
	 */
	protected function getAfterPortlet( $name ) {
		$html = '';
		$content = '';
		$this->getHookRunner()->onBaseTemplateAfterPortlet( $this, $name, $content );
		$content .= $this->getSkin()->getAfterPortlet( $name );

		if ( $content !== '' ) {
			$html = Html::rawElement(
				'div',
				[ 'class' => [ 'after-portlet', 'after-portlet-' . $name ] ],
				$content
			);
		}

		return $html;
	}

	/**
	 * @deprecated since 1.35 use Skin::makeLink
	 * @return string
	 */
	protected function makeLink( $key, $item, $options = [] ) {
		return $this->getSkin()->makeLink( $key, $item, $options );
	}

	/**
	 * @deprecated since 1.35 use Skin::makeListItem
	 * @return string
	 */
	public function makeListItem( $key, $item, $options = [] ) {
		return $this->getSkin()->makeListItem( $key, $item, $options );
	}

	/**
	 * @deprecated since 1.35 use Skin::makeSearchInput
	 */
	protected function makeSearchInput( $attrs = [] ) {
		return $this->getSkin()->makeSearchInput( $attrs );
	}

	/**
	 * @deprecated since 1.35 use Skin::makeSearchButton
	 */
	protected function makeSearchButton( $mode, $attrs = [] ) {
		return $this->getSkin()->makeSearchButton( $mode, $attrs );
	}

	/**
	 * Returns an array of footerlinks trimmed down to only those footer links that
	 * are valid.
	 * If you pass "flat" as an option then the returned array will be a flat array
	 * of footer icons instead of a key/value array of footerlinks arrays broken
	 * up into categories.
	 * @param string|null $option
	 * @return array|mixed
	 */
	protected function getFooterLinks( $option = null ) {
		$footerlinks = $this->get( 'footerlinks' );

		// Reduce footer links down to only those which are being used
		$validFooterLinks = [];
		foreach ( $footerlinks as $category => $links ) {
			$validFooterLinks[$category] = [];
			foreach ( $links as $link ) {
				if ( isset( $this->data[$link] ) && $this->data[$link] ) {
					$validFooterLinks[$category][] = $link;
				}
			}
			if ( count( $validFooterLinks[$category] ) <= 0 ) {
				unset( $validFooterLinks[$category] );
			}
		}

		if ( $option == 'flat' && count( $validFooterLinks ) ) {
			// fold footerlinks into a single array using a bit of trickery
			$validFooterLinks = array_merge( ...array_values( $validFooterLinks ) );
		}

		return $validFooterLinks;
	}

	/**
	 * Returns an array of footer icons filtered down by options relevant to how
	 * the skin wishes to display them.
	 * If you pass "icononly" as the option all footer icons which do not have an
	 * image icon set will be filtered out.
	 * If you pass "nocopyright" then MediaWiki's copyright icon will not be included
	 * in the list of footer icons. This is mostly useful for skins which only
	 * display the text from footericons instead of the images and don't want a
	 * duplicate copyright statement because footerlinks already rendered one.
	 * @param string|null $option
	 * @deprecated 1.35 read footer icons from template data requested via
	 *     $this->get('footericons')
	 * @return array
	 */
	protected function getFooterIcons( $option = null ) {
		// Generate additional footer icons
		$footericons = $this->get( 'footericons' );

		if ( $option == 'icononly' ) {
			// Unset any icons which don't have an image
			foreach ( $footericons as $footerIconsKey => &$footerIconsBlock ) {
				foreach ( $footerIconsBlock as $footerIconKey => $footerIcon ) {
					if ( !is_string( $footerIcon ) && !isset( $footerIcon['src'] ) ) {
						unset( $footerIconsBlock[$footerIconKey] );
					}
				}
				if ( $footerIconsBlock === [] ) {
					unset( $footericons[$footerIconsKey] );
				}
			}
		} elseif ( $option == 'nocopyright' ) {
			unset( $footericons['copyright'] );
		}

		return $footericons;
	}

	/**
	 * Renderer for getFooterIcons and getFooterLinks
	 *
	 * @param string $iconStyle $option for getFooterIcons: "icononly", "nocopyright"
	 * @param string $linkStyle $option for getFooterLinks: "flat"
	 *
	 * @return string html
	 * @since 1.29
	 */
	protected function getFooter( $iconStyle = 'icononly', $linkStyle = 'flat' ) {
		$validFooterIcons = $this->getFooterIcons( $iconStyle );
		$validFooterLinks = $this->getFooterLinks( $linkStyle );

		$html = '';

		if ( count( $validFooterIcons ) + count( $validFooterLinks ) > 0 ) {
			$html .= Html::openElement( 'div', [
				'id' => 'footer-bottom',
				'class' => 'mw-footer',
				'role' => 'contentinfo',
				'lang' => $this->get( 'userlang' ),
				'dir' => $this->get( 'dir' )
			] );
			$footerEnd = Html::closeElement( 'div' );
		} else {
			$footerEnd = '';
		}
		foreach ( $validFooterIcons as $blockName => $footerIcons ) {
			$html .= Html::openElement( 'div', [
				'id' => Sanitizer::escapeIdForAttribute( "f-{$blockName}ico" ),
				'class' => 'footer-icons'
			] );
			foreach ( $footerIcons as $icon ) {
				$html .= $this->getSkin()->makeFooterIcon( $icon );
			}
			$html .= Html::closeElement( 'div' );
		}
		if ( count( $validFooterLinks ) > 0 ) {
			$html .= Html::openElement( 'ul', [ 'id' => 'f-list', 'class' => 'footer-places' ] );
			foreach ( $validFooterLinks as $aLink ) {
				$html .= Html::rawElement(
					'li',
					[ 'id' => Sanitizer::escapeIdForAttribute( $aLink ) ],
					$this->get( $aLink )
				);
			}
			$html .= Html::closeElement( 'ul' );
		}

		$html .= $this->getClear() . $footerEnd;

		return $html;
	}

	/**
	 * Get a div with the core visualClear class, for clearing floats
	 *
	 * @return string html
	 * @since 1.29
	 */
	protected function getClear() {
		return Html::element( 'div', [ 'class' => 'visualClear' ] );
	}

	/**
	 * Get the suggested HTML for page status indicators: icons (or short text snippets) usually
	 * displayed in the top-right corner of the page, outside of the main content.
	 *
	 * Your skin may implement this differently, for example by handling some indicator names
	 * specially with a different UI. However, it is recommended to use a `<div class="mw-indicator"
	 * id="mw-indicator-<id>" />` as a wrapper element for each indicator, for better compatibility
	 * with extensions and user scripts.
	 *
	 * The raw data is available in `$this->data['indicators']` as an associative array (keys:
	 * identifiers, values: contents) internally ordered by keys.
	 *
	 * @return string HTML
	 * @since 1.25
	 */
	public function getIndicators() {
		$out = "<div class=\"mw-indicators mw-body-content\">\n";
		foreach ( $this->data['indicators'] as $id => $content ) {
			$out .= Html::rawElement(
				'div',
				[
					'id' => Sanitizer::escapeIdForAttribute( "mw-indicator-$id" ),
					'class' => 'mw-indicator',
				],
				$content
			) . "\n";
		}
		$out .= "</div>\n";
		return $out;
	}

	/**
	 * Output getTrail
	 */
	protected function printTrail() {
		echo $this->getTrail();
	}

	/**
	 * Get the basic end-page trail including bottomscripts, reporttime, and
	 * debug stuff. This should be called right before outputting the closing
	 * body and html tags.
	 *
	 * @return string|WrappedStringList HTML
	 * @since 1.29
	 */
	public function getTrail() {
		return WrappedString::join( "\n", [
			MWDebug::getDebugHTML( $this->getSkin()->getContext() ),
			$this->get( 'bottomscripts' ),
			$this->get( 'reporttime' )
		] );
	}
}
