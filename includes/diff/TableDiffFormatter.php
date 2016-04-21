<?php
/**
 * Portions taken from phpwiki-1.3.3.
 *
 * Copyright © 2000, 2001 Geoffrey T. Dairiki <dairiki@dairiki.org>
 * You may copy this code freely under the conditions of the GPL.
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
 * @ingroup DifferenceEngine
 */

/**
 * MediaWiki default table style diff formatter
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class TableDiffFormatter extends DiffFormatter {

	function __construct() {
		$this->leadingContextLines = 2;
		$this->trailingContextLines = 2;
	}

	/**
	 * @static
	 * @param string $msg
	 *
	 * @return mixed
	 */
	public static function escapeWhiteSpace( $msg ) {
		$msg = preg_replace( '/^ /m', '&#160; ', $msg );
		$msg = preg_replace( '/ $/m', ' &#160;', $msg );
		$msg = preg_replace( '/  /', '&#160; ', $msg );

		return $msg;
	}

	/**
	 * @param int $xbeg
	 * @param int $xlen
	 * @param int $ybeg
	 * @param int $ylen
	 *
	 * @return string
	 */
	protected function blockHeader( $xbeg, $xlen, $ybeg, $ylen ) {
		// '<!--LINE \d+ -->' get replaced by a localised line number
		// in DifferenceEngine::localiseLineNumbers
		$r = '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l' .
			$xbeg .
			'" ><!--LINE ' .
			$xbeg .
			"--></td>\n" .
			'<td colspan="2" class="diff-lineno"><!--LINE ' .
			$ybeg .
			"--></td></tr>\n";

		return $r;
	}

	/**
	 * Writes the header to the output buffer.
	 *
	 * @param string $header
	 */
	protected function startBlock( $header ) {
		$this->writeOutput( $header );
	}

	protected function endBlock() {
	}

	/**
	 * @param string[] $lines
	 * @param string $prefix
	 * @param string $color
	 */
	protected function lines( $lines, $prefix = ' ', $color = 'white' ) {
	}

	/**
	 * HTML-escape parameter before calling this
	 *
	 * @param string $line
	 *
	 * @return string
	 */
	protected function addedLine( $line ) {
		return $this->wrapLine( '+', 'diff-addedline', $line );
	}

	/**
	 * HTML-escape parameter before calling this
	 *
	 * @param string $line
	 *
	 * @return string
	 */
	protected function deletedLine( $line ) {
		return $this->wrapLine( '−', 'diff-deletedline', $line );
	}

	/**
	 * HTML-escape parameter before calling this
	 *
	 * @param string $line
	 *
	 * @return string
	 */
	protected function contextLine( $line ) {
		return $this->wrapLine( '&#160;', 'diff-context', $line );
	}

	/**
	 * @param string $marker
	 * @param string $class Unused
	 * @param string $line
	 *
	 * @return string
	 */
	protected function wrapLine( $marker, $class, $line ) {
		if ( $line !== '' ) {
			// The <div> wrapper is needed for 'overflow: auto' style to scroll properly
			$line = Xml::tags( 'div', null, $this->escapeWhiteSpace( $line ) );
		}

		return "<td class='diff-marker'>$marker</td><td class='$class'>$line</td>";
	}

	/**
	 * @return string
	 */
	protected function emptyLine() {
		return '<td colspan="2">&#160;</td>';
	}

	/**
	 * Writes all lines to the output buffer, each enclosed in <tr>.
	 *
	 * @param string[] $lines
	 */
	protected function added( $lines ) {
		foreach ( $lines as $line ) {
			$this->writeOutput( '<tr>' . $this->emptyLine() .
				$this->addedLine( '<ins class="diffchange">' .
					htmlspecialchars( $line ) . '</ins>' ) . "</tr>\n" );
		}
	}

	/**
	 * Writes all lines to the output buffer, each enclosed in <tr>.
	 *
	 * @param string[] $lines
	 */
	protected function deleted( $lines ) {
		foreach ( $lines as $line ) {
			$this->writeOutput( '<tr>' . $this->deletedLine( '<del class="diffchange">' .
					htmlspecialchars( $line ) . '</del>' ) .
				$this->emptyLine() . "</tr>\n" );
		}
	}

	/**
	 * Writes all lines to the output buffer, each enclosed in <tr>.
	 *
	 * @param string[] $lines
	 */
	protected function context( $lines ) {
		foreach ( $lines as $line ) {
			$this->writeOutput( '<tr>' .
				$this->contextLine( htmlspecialchars( $line ) ) .
				$this->contextLine( htmlspecialchars( $line ) ) . "</tr>\n" );
		}
	}

	/**
	 * Writes the two sets of lines to the output buffer, each enclosed in <tr>.
	 *
	 * @param string[] $orig
	 * @param string[] $closing
	 */
	protected function changed( $orig, $closing ) {

		$diff = new WordLevelDiff( $orig, $closing );
		$del = $diff->orig();
		$add = $diff->closing();

		# Notice that WordLevelDiff returns HTML-escaped output.
		# Hence, we will be calling addedLine/deletedLine without HTML-escaping.

		$ndel = count( $del );
		$nadd = count( $add );
		$n = max( $ndel, $nadd );
		for ( $i = 0; $i < $n; $i++ ) {
			$delLine = $i < $ndel ? $this->deletedLine( $del[$i] ) : $this->emptyLine();
			$addLine = $i < $nadd ? $this->addedLine( $add[$i] ) : $this->emptyLine();
			$this->writeOutput( "<tr>{$delLine}{$addLine}</tr>\n" );
		}
	}

}
