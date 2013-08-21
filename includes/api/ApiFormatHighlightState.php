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
 * Class for matching plain text substrings and wrapping them in HTML tags,
 * holding intermediate results between passes.
 *
 * @ingroup API
 * @since 1.24
 */
class ApiFormatHighlightState {
	/**
	 * The text to highlight, as modified by any replacements that have taken place.
	 */
	protected $text;

	/**
	 * Map from encoded markers to the HTML fragments they represent.
	 */
	protected $markers = array();

	/**
	 * The ID of the current marker set.
	 */
	protected $markerSetId = 0;

	/**
	 * The next marker ID in the current marker set to use.
	 */
	protected $nextMarkerId = 0;

	/**
	 * @param string $text The text to highlight
	 */
	public function __construct( $text ) {
		if ( preg_match( '/[' . $this->getMarkerRange() . ']/S', $text ) ) {
			// This shouldn't happen; the API is supposed to replace characters that are
			// forbidden in XML before encoding them, and no current output formats give
			// any of those bytes a special meaning.
			throw new MWException( 'Forbidden marker byte found in text to highlight' );
		}

		$this->text = $text;
	}

	/**
	 * Get the range in which marker characters fall.
	 *
	 * This is intended for use in negative character classes to prevent matches
	 * from unexpectedly ending inside other HTML elements, ensuring well-formedness
	 * of the generated HTML.
	 *
	 * @return string
	 */
	public function getMarkerRange() {
		return '\x01\x02\x03\x10-\x1f';
	}

	/**
	 * Start a new marker set so the next added marker will have an ID of 0.
	 */
	public function startNewMarkerSet() {
		++$this->markerSetId;
		$this->nextMarkerId = 0;
	}

	/**
	 * Add a marker that represents a given fragment of HTML.
	 *
	 * @param string $html The HTML that the marker stands for
	 * @return string The new marker
	 */
	public function addMarker( $html ) {
		$text = $this->getMarker( $this->nextMarkerId++ );
		$this->markers[$text] = $html;

		return $text;
	}

	/**
	 * Get the marker string for a given marker ID.
	 *
	 * @param int $id The marker's ID
	 * @param int $setOffset Marker set offset (0 for current set, -1 for previous set)
	 * @return string The marker's text
	 */
	public function getMarker( $id, $setOffset = 0 ) {
		$setId = $this->markerSetId + $setOffset;
		if ( $setId < 0 ) {
			throw new MWException( 'Marker set offset out of range' );
		}

		$from = '0123456789abcdef';
		$to = "\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f";

		return strtr( sprintf( "\x01%x\x02%x\x03", $setId, $id ), $from, $to );
	}

	/**
	 * Replace all occurrences of a plain text string with an HTML fragment.
	 *
	 * @param string|array $search Plain text being searched for
	 * @param string|array $replace HTML to replace the found text
	 */
	public function strReplaceHTML( $search, $replace ) {
		$search = (array)$search;
		$replace = (array)$replace;

		foreach ( $replace as $rIndex => $repl ) {
			$replace[$rIndex] = $this->addMarker( $repl );
		}

		$this->text = str_replace( $search, $replace, $this->text );
	}

	/**
	 * Replace plain text matches of a regular expression with an HTML fragment.
	 *
	 * The HTML fragment is split at each backreference, and each non-empty part gets
	 * its own marker. Captured text will remain as-is (to be HTML escaped later).
	 *
	 * @warning Do not insert backreferences inside attribute values! Instead use
	 * ApiFormatHighlightState::pregReplaceCallback() so the attributes can be hidden
	 * from later replacement passes (cf. bug 61362).
	 *
	 * @param string|array $pattern The pattern to search for
	 * @param string|array $replacement The HTML fragment
	 */
	public function pregReplaceHTML( $pattern, $replacement ) {
		$pattern = (array)$pattern;
		$replacement = (array)$replacement;

		foreach ( $replacement as $rIndex => $repl ) {
			// See php_pcre_replace_impl() and preg_get_backref() in php_pcre.c
			$parts = preg_split(
				'/(?<!\\\\)([$\\\\][0-9]{1,2}|\$\{[0-9]{1,2}\})/',
				$repl, -1, PREG_SPLIT_DELIM_CAPTURE
			);

			foreach ( $parts as $pIndex => $part ) {
				if ( $pIndex % 2 === 0 && $part !== '' ) {
					$parts[$pIndex] = $this->addMarker( $part );
				}
			}

			$replacement[$rIndex] = implode( '', $parts );
		}

		$this->text = preg_replace( $pattern, $replacement, $this->text );
	}

	/**
	 * Replace plain text matches of a regular expression using a callback that returns a
	 * plain text fragment.
	 *
	 * The callback can insert an HTML fragment by returning a marker, which can be obtained
	 * by calling ApiFormatHighlightState::addMarker().
	 *
	 * @param string $pattern The pattern to search for
	 * @param callable $callback Callback accepting an array of matched elements
	 */
	public function pregReplaceCallback( $pattern, $callback ) {
		$this->text = preg_replace_callback( $pattern, $callback, $this->text );
	}

	/**
	 * HTML escape the text, replacing all markers with the HTML fragments they represent.
	 *
	 * @return string The resulting HTML
	 */
	public function getHTML() {
		$ra = new ReplacementArray( $this->markers );
		$html = $ra->replace( htmlspecialchars( $this->text, ENT_QUOTES, 'UTF-8' ) );

		if ( preg_match( '/[' . $this->getMarkerRange() . ']/S', $html ) ) {
			throw new MWException( 'Marker byte found in generated HTML (bug in formatter?)' );
		}

		return $html;
	}
}
