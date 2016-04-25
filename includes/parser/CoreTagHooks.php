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
	 * @param $parser Parser
	 * @return void
	 */
	static function register( $parser ) {
		global $wgRawHtml;
		$parser->setHook( 'pre', array( __CLASS__, 'pre' ) );
		$parser->setHook( 'nowiki', array( __CLASS__, 'nowiki' ) );
		$parser->setHook( 'gallery', array( __CLASS__, 'gallery' ) );
		if ( $wgRawHtml ) {
			$parser->setHook( 'html', array( __CLASS__, 'html' ) );
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
	static function pre( $text, $attribs, $parser ) {
		// Backwards-compatibility hack
		$content = StringUtils::delimiterReplace( '<nowiki>', '</nowiki>', '$1', $text, 'i' );

		$attribs = Sanitizer::validateTagAttributes( $attribs, 'pre' );
		// We need to let both '"' and '&' through,
		// for strip markers and entities respectively.
		$content = str_replace(
			array( '>', '<' ),
			array( '&gt;', '&lt;' ),
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
	 * @param $content string
	 * @param $attributes array
	 * @param $parser Parser
	 * @throws MWException
	 * @return array
	 */
	static function html( $content, $attributes, $parser ) {
		global $wgRawHtml;
		if ( $wgRawHtml ) {
			return array( $content, 'markerType' => 'nowiki' );
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
	 * @param $content string
	 * @param $attributes array
	 * @param $parser Parser
	 * @return array
	 */
	static function nowiki( $content, $attributes, $parser ) {
		$content = strtr( $content, array(
			// lang converter
			'-{' => '-&#123;',
			'}-' => '&#125;-',
			// html tags
			'<' => '&lt;',
			'>' => '&gt;'
			// Note: Both '"' and '&' are not converted.
			// This allows strip markers and entities through.
		) );
		return array( $content, 'markerType' => 'nowiki' );
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
	static function gallery( $content, $attributes, $parser ) {
		return $parser->renderImageGallery( $content, $attributes );
	}
}
