<?php
/**
 * Tag hooks provided by MediaWiki core
 *
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
 * @ingroup Parser
 */

/**
 * Various tag hooks, registered in Parser::firstCallInit()
 * @ingroup Parser
 */
class CoreTagHooks {
	/**
	 * @param Parser $parser
	 * @return void
	 */
	public static function register( $parser ) {
		global $wgRawHtml;
		$parser->setHook( 'pre', [ __CLASS__, 'pre' ] );
		$parser->setHook( 'nowiki', [ __CLASS__, 'nowiki' ] );
		$parser->setHook( 'gallery', [ __CLASS__, 'gallery' ] );
		$parser->setHook( 'indicator', [ __CLASS__, 'indicator' ] );
		$parser->setHook( 'mw-icon', [ __CLASS__, 'mwicon' ] );
		$parser->setHook( 'mw-button', [ __CLASS__, 'mwbutton' ] );
		if ( $wgRawHtml ) {
			$parser->setHook( 'html', [ __CLASS__, 'html' ] );
		}
	}

	/**
	 * Core parser tag hook function for 'pre'.
	 * Text is treated roughly as 'nowiki' wrapped in an HTML 'pre' tag;
	 * valid HTML attributes are passed on.
	 *
	 * @param string $text
	 * @param array $attribs
	 * @param Parser $parser
	 * @return string HTML
	 */
	public static function pre( $text, $attribs, $parser ) {
		// Backwards-compatibility hack
		$content = StringUtils::delimiterReplace( '<nowiki>', '</nowiki>', '$1', $text, 'i' );

		$attribs = Sanitizer::validateTagAttributes( $attribs, 'pre' );
		// We need to let both '"' and '&' through,
		// for strip markers and entities respectively.
		$content = str_replace(
			[ '>', '<' ],
			[ '&gt;', '&lt;' ],
			$content
		);
		return Html::rawElement( 'pre', $attribs, $content );
	}

	/**
	 * Core parser tag hook function for 'html', used only when
	 * $wgRawHtml is enabled.
	 *
	 * This is potentially unsafe and should be used only in very careful
	 * circumstances, as the contents are emitted as raw HTML.
	 *
	 * Uses undocumented extended tag hook return values, introduced in r61913.
	 *
	 * @param string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @throws MWException
	 * @return array|string Output of tag hook
	 */
	public static function html( $content, $attributes, $parser ) {
		global $wgRawHtml;
		if ( $wgRawHtml ) {
			if ( $parser->getOptions()->getAllowUnsafeRawHtml() ) {
				return [ $content, 'markerType' => 'nowiki' ];
			} else {
				// In a system message where raw html is
				// not allowed (but it is allowed in other
				// contexts).
				return Html::rawElement(
					'span',
					[ 'class' => 'error' ],
					// Using ->text() not ->parse() as
					// a paranoia measure against a loop.
					wfMessage( 'rawhtml-notallowed' )->escaped()
				);
			}
		} else {
			throw new MWException( '<html> extension tag encountered unexpectedly' );
		}
	}

	/**
	 * Core parser tag hook function for 'nowiki'. Text within this section
	 * gets interpreted as a string of text with HTML-compatible character
	 * references, and wiki markup within it will not be expanded.
	 *
	 * Uses undocumented extended tag hook return values, introduced in r61913.
	 *
	 * @param string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @return array
	 */
	public static function nowiki( $content, $attributes, $parser ) {
		$content = strtr( $content, [
			// lang converter
			'-{' => '-&#123;',
			'}-' => '&#125;-',
			// html tags
			'<' => '&lt;',
			'>' => '&gt;'
			// Note: Both '"' and '&' are not converted.
			// This allows strip markers and entities through.
		] );
		return [ $content, 'markerType' => 'nowiki' ];
	}

	/**
	 * Core parser tag hook function for 'gallery'.
	 *
	 * Renders a thumbnail list of the given images, with optional captions.
	 * Full syntax documented on the wiki:
	 *
	 *   https://www.mediawiki.org/wiki/Help:Images#Gallery_syntax
	 *
	 * @todo break Parser::renderImageGallery out here too.
	 *
	 * @param string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @return string HTML
	 */
	public static function gallery( $content, $attributes, $parser ) {
		return $parser->renderImageGallery( $content, $attributes );
	}

	/**
	 * XML-style tag for page status indicators: icons (or short text snippets) usually displayed in
	 * the top-right corner of the page, outside of the main content.
	 *
	 * @param string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @return string
	 * @since 1.25
	 */
	public static function indicator( $content, array $attributes, Parser $parser, PPFrame $frame ) {
		if ( !isset( $attributes['name'] ) || trim( $attributes['name'] ) === '' ) {
			return '<span class="error">' .
				wfMessage( 'invalid-indicator-name' )->inContentLanguage()->parse() .
				'</span>';
		}

		$parser->getOutput()->setIndicator(
			trim( $attributes['name'] ),
			Parser::stripOuterParagraph( $parser->recursiveTagParseFully( $content, $frame ) )
		);

		return '';
	}

	/**
	 * XML-style tag for OOUI icons.
	 *
	 * @param string $content Icon name
	 * @param array $attributes
	 *   - `title`: icon tooltip for accessibility
	 *   - `size`: size multiplier
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @return string
	 * @since 1.32
	 */
	public static function mwicon( $content, array $attributes, Parser $parser, PPFrame $frame ) {
		$parser->enableOOUI();

		$config = [];

		if ( $content ) {
			if ( preg_match( '/^[\w-]{1,100}$/', $content ) ) {
				$config['icon'] = $content;
				$parser->getOutput()->addModuleStyles( "oojs-ui.styles.icon.{$content}" );
			} else {
				return '<span class="error">' .
					wfMessage( 'tag-mw-icon-invalid-icon' )->inContentLanguage()->parse() .
					'</span>';
			}
		} else {
			return '<span class="error">' .
				wfMessage( 'tag-mw-icon-must-have-content' )->inContentLanguage()->parse() .
				'</span>';
		}

		if ( isset( $attributes['title'] ) ) {
			$config['title'] = $attributes['title'];
		}

		$icon = new OOUI\IconWidget( $config );
		$icon = $icon->toString();
		if ( MWTidy::singleton() instanceof MediaWiki\Tidy\RaggettBase ) {
			// Prevent Tidy from removing empty <span> tags (used to display the icon)
			$icon = str_replace( '></span>', '><!-- --></span>', $icon );
		}

		if ( isset( $attributes['size'] ) ) {
			$size = floatval( $attributes['size'] ) * 100;
			$icon = "<span style=\"font-size: $size%;\">$icon</span>";
		}

		// Prevent further parsing
		return [ $icon, 'markerType' => 'nowiki' ];
	}

	/**
	 * XML-style tag for OOUI clickable button links.
	 *
	 * A button must have a valid `link` attribute, and content or valid `icon` (or both).
	 * All other attributes are optional.
	 *
	 * @param string $content Button label
	 * @param array $attributes
	 *   - `link`: internal or external link target, treated like 'link' parameter in image syntax
	 *   - `title`: link tooltip, default is generated from `link`
	 *   - `icon`: name of the icon to display next to button label
	 *   - `flags`: space-separated list of flags to apply, e.g. progressive/destructive/primary
	 *   - `frameless`: whether to show a covert button that pretends not to be one (boolean)
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @return string
	 * @since 1.32
	 */
	public static function mwbutton( $content, array $attributes, Parser $parser, PPFrame $frame ) {
		$parser->enableOOUI();

		$config = [];

		if ( $content ) {
			$parsed = $parser->recursiveTagParseFully( $content, $frame );
			$stripped = Parser::stripOuterParagraph( $parsed );
			if ( $parsed === $stripped ) {
				return '<span class="error">' .
					wfMessage( 'tag-mw-button-must-be-single-line' )->inContentLanguage()->parse() .
					'</span>';
			}
			if ( strpos( $stripped, '<a ' ) !== false ) {
				return '<span class="error">' .
					wfMessage( 'tag-mw-button-must-not-nest-links' )->inContentLanguage()->parse() .
					'</span>';
			}
			$config['label'] = new OOUI\HtmlSnippet( $stripped );
		}

		if ( isset( $attributes['icon'] ) ) {
			if ( preg_match( '/^[\w-]{1,100}$/', $attributes['icon'] ) ) {
				$config['icon'] = $attributes['icon'];
				$parser->getOutput()->addModuleStyles( "oojs-ui.styles.icon.{$attributes['icon']}" );
			} else {
				return '<span class="error">' .
					wfMessage( 'tag-mw-icon-invalid-icon' )->inContentLanguage()->parse() .
					'</span>';
			}
		}

		if ( !isset( $config['label'] ) && !isset( $config['icon'] ) ) {
			return '<span class="error">' .
				wfMessage( 'tag-mw-button-must-have-content' )->inContentLanguage()->parse() .
				'</span>';
		}

		if ( isset( $attributes['link'] ) ) {
			list( $type, $target ) = $parser->parseLinkParameter( $attributes['link'] );
			// We intentionally do not add any classes like 'new' or 'redirect' to button links
			if ( $type === 'link-url' ) {
				$config['href'] = $target;
				if ( $parser->mOptions->getExternalLinkTarget() ) {
					$config['target'] = $parser->mOptions->getExternalLinkTarget();
				}
				$config['noFollow'] = Parser::getExternalLinkRel( $target ) === 'nofollow';
			} elseif ( $type === 'link-title' ) {
				$config['href'] = $target->getLinkURL();
				$config['title'] = $target->getPrefixedText();
				$config['noFollow'] = false;
			}
		}
		if ( !isset( $config['href'] ) ) {
			return '<span class="error">' .
				wfMessage( 'tag-mw-button-must-have-link' )->inContentLanguage()->parse() .
				'</span>';
		}
		if ( isset( $attributes['title'] ) ) {
			// Overwrites the default from 'link', if any
			$config['title'] = $attributes['title'];
		}

		if ( isset( $attributes['frameless'] ) ) {
			$config['framed'] = false;
		}
		if ( isset( $attributes['flags'] ) ) {
			$config['flags'] = array_filter( array_map( 'trim', explode( ' ', $attributes['flags'] ) ) );
		}

		$button = new OOUI\ButtonWidget( $config );
		$button = $button->toString();
		if ( MWTidy::singleton() instanceof MediaWiki\Tidy\RaggettBase ) {
			// Prevent Tidy from removing empty <span> tags (used to display the icon)
			$button = str_replace( '></span>', '><!-- --></span>', $button );
		}
		// Prevent further parsing
		return [ $button, 'markerType' => 'nowiki' ];
	}
}
