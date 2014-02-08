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
		$parser->setHook( 'lines', array( __CLASS__, 'lines' ) );
		$parser->setHook( 'poem', array( __CLASS__, 'lines' ) ); # legacy alias
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
		return Xml::openElement( 'pre', $attribs ) .
			Xml::escapeTagsOnly( $content ) .
			'</pre>';
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
		$content = strtr( $content, array( '-{' => '-&#123;', '}-' => '&#125;-' ) );
		return array( Xml::escapeTagsOnly( $content ), 'markerType' => 'nowiki' );
	}

	/**
	 * Core parser tag hook function for 'gallery'.
	 *
	 * Renders a thumbnail list of the given images, with optional captions.
	 * Full syntax documented on the wiki:
	 *
	 *   http://www.mediawiki.org/wiki/Help:Images#Gallery_syntax
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

	/**
	 * Core parser tag hook function for 'lines', aka 'poem'.
	 *
	 * Outputs text, maintaining line breaks and providing a smaller, line-based
	 * indentation for line-initial colons instead of the default <dl>. Suitable
	 * for line-based text such as poetry and similar content.
	 *
	 * @param string $content The text inside the <lines> tag
	 * @param array $attributes
	 * @param Parser $parser
	 * @param boolean $frame
	 * @return string
	 */
	static function lines( $content, $attributes, $parser, $frame = false ) {
		// replace colons with indented spans
		$text = preg_replace_callback( '/^(:+)(.+)$/m', array( __CLASS__, 'linesIndentVerse' ), $content );

		// replace spaces at the beginning of a line with non-breaking spaces
		$text = preg_replace_callback( '/^( +)/m', array( __CLASS__, 'linesReplaceSpaces' ), $text );

		$text = $parser->recursiveTagParse( $text, $frame );

		$attribs = Sanitizer::validateTagAttributes( $attributes, 'div' );

		// Wrap output in a <pre> with "poem" class.
		if ( isset( $attribs['class'] ) ) {
			$attribs['class'] = 'poem ' . $attribs['class'];
		} else {
			$attribs['class'] = 'poem';
		}

		return Html::rawElement( 'pre', $attribs, $text );
	}

	/**
	 * Callback for preg_replace_callback() that replaces spaces with non-breaking spaces
	 * @param array $m Matches from the regular expression
	 *   - $m[1] consists of 1 or more spaces
	 * @return string
	 */
	protected static function linesReplaceSpaces( $m ) {
		return str_replace( ' ', '&#160;', $m[1] );
	}

	/**
	 * Callback for preg_replace_callback() that wraps content in an indented span
	 * @param array $m Matches from the regular expression
	 *   - $m[1] consists of 1 or more colons
	 *   - $m[2] consists of the text after the colons
	 * @return string
	 */
	protected static function linesIndentVerse( $m ) {
		$attribs = array(
			'class' => 'mw-poem-indented',
			'style' => 'margin-left: ' . strlen( $m[1] ) . 'em;'
		);
		return Html::rawElement( 'span', $attribs, $m[2] );
	}
}
