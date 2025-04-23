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

namespace MediaWiki\Skin;

use MediaWiki\Html\Html;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * Extended QuickTemplate with additional MediaWiki-specific helper methods.
 *
 * @todo Phase this class out and make it an alias for QuickTemplate. Move methods
 *  individually as-appropriate either down to QuickTemplate, or (with deprecation)
 *  up to SkinTemplate.
 *
 * @stable to extend
 */
abstract class BaseTemplate extends QuickTemplate {

	/**
	 * Get a Message object with its context set
	 *
	 * @param string $name Message name
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return Message
	 */
	public function getMsg( $name, ...$params ) {
		return $this->getSkin()->msg( $name, ...$params );
	}

	public function msg( $str ) {
		echo $this->getMsg( $str )->escaped();
	}

	/**
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
			// @phan-suppress-next-line PhanTypeMismatchDimAssignment False positive
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
						'content' => $content,
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

		if ( isset( $options['htmlOnly'] ) && $options['htmlOnly'] === true ) {
			foreach ( $boxes as $boxName => $box ) {
				if ( is_array( $box['content'] ) ) {
					$content = '<ul>';
					foreach ( $box['content'] as $key => $val ) {
						$content .= "\n	" . $this->getSkin()->makeListItem( $key, $val );
					}
					$content .= "\n</ul>\n";
					$boxes[$boxName]['content'] = $content;
				}
			}
		}

		return $boxes;
	}

	/**
	 * Wrapper for Skin method.
	 *
	 * @param string $key of link
	 * @param array $item to render
	 * @param array $options for link
	 * @return string
	 */
	protected function makeLink( $key, $item, $options = [] ) {
		return $this->getSkin()->makeLink( $key, $item, $options );
	}

	/**
	 * Wrapper for Skin method.
	 *
	 * @param string $key of list item
	 * @param array $item to render
	 * @param array $options for list item
	 * @return string
	 */
	public function makeListItem( $key, $item, $options = [] ) {
		return $this->getSkin()->makeListItem( $key, $item, $options );
	}

	/**
	 * Wrapper for Skin method.
	 *
	 * @param array $attrs
	 * @return string
	 */
	protected function makeSearchInput( $attrs = [] ) {
		return $this->getSkin()->makeSearchInput( $attrs );
	}

	/**
	 * Wrapper for Skin method.
	 *
	 * @param string $mode
	 * @param array $attrs
	 * @return string
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
	 * @return array
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
	 * Unsets any elements in an array of icon definitions which do
	 * not have src attributes or are not strings.
	 */
	private function unsetIconsWithoutImages( array &$icons ) {
		// Unset any icons which don't have an image
		foreach ( $icons as $iconsKey => &$iconsBlock ) {
			foreach ( $iconsBlock as $iconKey => $icon ) {
				if ( !is_string( $icon ) && !isset( $icon['src'] ) ) {
					unset( $iconsBlock[$iconKey] );
				}
			}
			if ( $iconsBlock === [] ) {
				unset( $icons[$iconsKey] );
			}
		}
	}

	/**
	 * Renderer for getFooterLinks
	 *
	 * @param string $iconStyle $option when set to "icononly" will not display any
	 *  icons which do not have images.
	 * @param string $linkStyle $option for getFooterLinks: "flat"
	 *
	 * @return string html
	 * @since 1.29
	 */
	protected function getFooter( $iconStyle = 'icononly', $linkStyle = 'flat' ) {
		$validFooterIcons = $this->get( 'footericons' );
		if ( $iconStyle === 'icononly' ) {
			$this->unsetIconsWithoutImages( $validFooterIcons );
		}
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
		$out = "<div class=\"mw-indicators\">\n";
		foreach ( $this->data['indicators'] as $id => $content ) {
			$out .= Html::rawElement(
				'div',
				[
					'id' => Sanitizer::escapeIdForAttribute( "mw-indicator-$id" ),
					'class' => 'mw-indicator',
				],
				$content
			) .
			// Add whitespace between the <div>s because
			// they get displayed with display: inline-block
			"\n";
		}
		$out .= "</div>\n";
		return $out;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( BaseTemplate::class, 'BaseTemplate' );
