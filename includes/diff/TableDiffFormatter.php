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
 * @newable
 * @ingroup DifferenceEngine
 */
class TableDiffFormatter extends DiffFormatter {

	/**
	 * Constants for diff sides. Note: these are also used for context lines.
	 */
	private const SIDE_DELETED = 'deleted';
	private const SIDE_ADDED = 'added';
	private const SIDE_CLASSES = [
		self::SIDE_DELETED => 'diff-side-deleted',
		self::SIDE_ADDED => 'diff-side-added'
	];

	public function __construct() {
		$this->leadingContextLines = 2;
		$this->trailingContextLines = 2;
	}

	/**
	 * @param string $msg
	 *
	 * @return string
	 */
	public static function escapeWhiteSpace( $msg ) {
		$msg = preg_replace( '/^ /m', "\u{00A0} ", $msg );
		$msg = preg_replace( '/ $/m', " \u{00A0}", $msg );
		$msg = preg_replace( '/  /', "\u{00A0} ", $msg );

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
		return Html::rawElement(
			'tr',
			[],
			Html::rawElement(
				'td',
				[ 'colspan' => '2',  'class' => 'diff-lineno', 'id' => 'mw-diff-left-l' . $xbeg ],
				'<!--LINE ' . $xbeg . '-->'
			) .
			"\n" .
			Html::rawElement(
				'td',
				[ 'colspan' => '2',  'class' => 'diff-lineno' ],
				'<!--LINE ' . $ybeg . '-->'
			)
		) . "\n";
	}

	/** @inheritDoc */
	protected function startBlock( $header ) {
		$this->writeOutput( $header );
	}

	/** @inheritDoc */
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
		return $this->wrapLine( '+', [ 'diff-addedline', $this->getClassForSide( self::SIDE_ADDED ) ], $line );
	}

	/**
	 * HTML-escape parameter before calling this
	 *
	 * @param string $line
	 *
	 * @return string
	 */
	protected function deletedLine( $line ) {
		return $this->wrapLine( '−', [ 'diff-deletedline', $this->getClassForSide( self::SIDE_DELETED ) ], $line );
	}

	/**
	 * HTML-escape parameter before calling this
	 *
	 * @param string $line
	 * @param string $side self::SIDE_DELETED or self::SIDE_ADDED
	 *
	 * @return string
	 */
	protected function contextLine( $line, string $side ) {
		return $this->wrapLine( '', [ 'diff-context', $this->getClassForSide( $side ) ], $line );
	}

	/**
	 * @param string $marker
	 * @param string|string[] $class A single class or a list of classes
	 * @param string $line
	 *
	 * @return string
	 */
	protected function wrapLine( $marker, $class, $line ) {
		if ( $line !== '' ) {
			// The <div> wrapper is needed for 'overflow: auto' style to scroll properly
			$line = Html::rawElement( 'div', [], $this->escapeWhiteSpace( $line ) );
		} else {
			$line = Html::element( 'br' );
		}

		$markerAttrs = [ 'class' => 'diff-marker' ];
		if ( $marker ) {
			$markerAttrs['data-marker'] = $marker;
		}

		return Html::element( 'td', $markerAttrs ) .
			Html::rawElement( 'td', [ 'class' => $class ], $line );
	}

	/**
	 * @param string $side self::SIDE_DELETED or self::SIDE_ADDED
	 * @return string
	 */
	protected function emptyLine( string $side ) {
		return Html::element( 'td', [ 'colspan' => '2', 'class' => $this->getClassForSide( $side ) ] );
	}

	/**
	 * Writes all lines to the output buffer, each enclosed in <tr>.
	 *
	 * @param string[] $lines
	 */
	protected function added( $lines ) {
		foreach ( $lines as $line ) {
			$this->writeOutput(
				Html::rawElement(
					'tr',
					[],
					$this->emptyLine( self::SIDE_DELETED ) .
					$this->addedLine(
						Html::element(
							'ins',
							[ 'class' => 'diffchange' ],
							$line
						)
					)
				) .
				"\n"
			);
		}
	}

	/**
	 * Writes all lines to the output buffer, each enclosed in <tr>.
	 *
	 * @param string[] $lines
	 */
	protected function deleted( $lines ) {
		foreach ( $lines as $line ) {
			$this->writeOutput(
				Html::rawElement(
					'tr',
					[],
					$this->deletedLine(
						Html::element(
							'del',
							[ 'class' => 'diffchange' ],
							$line
						)
					) .
					$this->emptyLine( self::SIDE_ADDED )
				) .
				"\n"
			);
		}
	}

	/**
	 * Writes all lines to the output buffer, each enclosed in <tr>.
	 *
	 * @param string[] $lines
	 */
	protected function context( $lines ) {
		foreach ( $lines as $line ) {
			$this->writeOutput(
				Html::rawElement(
					'tr',
					[],
					$this->contextLine( htmlspecialchars( $line ), self::SIDE_DELETED ) .
					$this->contextLine( htmlspecialchars( $line ), self::SIDE_ADDED )
				) .
				"\n"
			);
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
			$delLine = $i < $ndel ? $this->deletedLine( $del[$i] ) : $this->emptyLine( self::SIDE_DELETED );
			$addLine = $i < $nadd ? $this->addedLine( $add[$i] ) : $this->emptyLine( self::SIDE_ADDED );
			$this->writeOutput(
				Html::rawElement(
					'tr',
					[],
					$delLine . $addLine
				) .
				"\n"
			);
		}
	}

	/**
	 * Get a class for the given diff side, or throw if the side is invalid.
	 *
	 * @param string $side self::SIDE_DELETED or self::SIDE_ADDED
	 * @return string
	 * @throws InvalidArgumentException
	 */
	private function getClassForSide( string $side ): string {
		if ( !isset( self::SIDE_CLASSES[$side] ) ) {
			throw new InvalidArgumentException( "Invalid diff side: $side" );
		}
		return self::SIDE_CLASSES[$side];
	}
}
