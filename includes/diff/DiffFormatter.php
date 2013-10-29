<?php
/**
 * Diff rendering classes. Portions taken from phpwiki-1.3.3.
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
 * Base class for diff formatters
 *
 * This class formats the diff in classic diff format.
 * It is intended that this class be customized via inheritance,
 * to obtain fancier outputs.
 * @todo document
 * @ingroup DifferenceEngine
 */
abstract class DiffFormatter {
	/**
	 * Number of leading context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses
	 * may want to set this to other values.
	 */
	protected $leadingContextLines = 0;

	/**
	 * Number of trailing context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses
	 * may want to set this to other values.
	 */
	protected $trailingContextLines = 0;

	/**
	 * Format a diff.
	 *
	 * @param $diff Diff A Diff object.
	 * @return string The formatted output.
	 */
	public function format( $diff ) {
		wfProfileIn( __METHOD__ );

		$xi = $yi = 1;
		$block = false;
		$context = array();

		$nlead = $this->leadingContextLines;
		$ntrail = $this->trailingContextLines;

		$this->startDiff();

		foreach ( $diff->edits as $edit ) {
			if ( $edit->type == 'copy' ) {
				if ( is_array( $block ) ) {
					if ( count( $edit->orig ) <= $nlead + $ntrail ) {
						$block[] = $edit;
					} else {
						if ( $ntrail ) {
							$context = array_slice( $edit->orig, 0, $ntrail );
							$block[] = new DiffOp_Copy( $context );
						}
						$this->block( $x0, $ntrail + $xi - $x0,
							$y0, $ntrail + $yi - $y0,
							$block );
						$block = false;
					}
				}
				$context = $edit->orig;
			} else {
				if ( !is_array( $block ) ) {
					$context = array_slice( $context, count( $context ) - $nlead );
					$x0 = $xi - count( $context );
					$y0 = $yi - count( $context );
					$block = array();
					if ( $context ) {
						$block[] = new DiffOp_Copy( $context );
					}
				}
				$block[] = $edit;
			}

			if ( $edit->orig ) {
				$xi += count( $edit->orig );
			}
			if ( $edit->closing ) {
				$yi += count( $edit->closing );
			}
		}

		if ( is_array( $block ) ) {
			$this->block( $x0, $xi - $x0,
				$y0, $yi - $y0,
				$block );
		}

		$end = $this->endDiff();
		wfProfileOut( __METHOD__ );
		return $end;
	}

	/**
	 * @param $xbeg
	 * @param $xlen
	 * @param $ybeg
	 * @param $ylen
	 * @param $edits
	 */
	protected function block( $xbeg, $xlen, $ybeg, $ylen, &$edits ) {
		wfProfileIn( __METHOD__ );
		$this->startBlock( $this->blockHeader( $xbeg, $xlen, $ybeg, $ylen ) );
		foreach ( $edits as $edit ) {
			if ( $edit->type == 'copy' ) {
				$this->context( $edit->orig );
			} elseif ( $edit->type == 'add' ) {
				$this->added( $edit->closing );
			} elseif ( $edit->type == 'delete' ) {
				$this->deleted( $edit->orig );
			} elseif ( $edit->type == 'change' ) {
				$this->changed( $edit->orig, $edit->closing );
			} else {
				throw new MWException( "Unknown edit type: {$edit->type}" );
			}
		}
		$this->endBlock();
		wfProfileOut( __METHOD__ );
	}

	protected function startDiff() {
		ob_start();
	}

	/**
	 * @return string
	 */
	protected function endDiff() {
		$val = ob_get_contents();
		ob_end_clean();
		return $val;
	}

	/**
	 * @param $xbeg
	 * @param $xlen
	 * @param $ybeg
	 * @param $ylen
	 * @return string
	 */
	protected function blockHeader( $xbeg, $xlen, $ybeg, $ylen ) {
		if ( $xlen > 1 ) {
			$xbeg .= ',' . ( $xbeg + $xlen - 1 );
		}
		if ( $ylen > 1 ) {
			$ybeg .= ',' . ( $ybeg + $ylen - 1 );
		}

		return $xbeg . ( $xlen ? ( $ylen ? 'c' : 'd' ) : 'a' ) . $ybeg;
	}

	protected function startBlock( $header ) {
		echo $header . "\n";
	}

	protected function endBlock() {
	}

	/**
	 * @param $lines
	 * @param $prefix string
	 */
	protected function lines( $lines, $prefix = ' ' ) {
		foreach ( $lines as $line ) {
			echo "$prefix $line\n";
		}
	}

	/**
	 * @param $lines
	 */
	protected function context( $lines ) {
		$this->lines( $lines );
	}

	/**
	 * @param $lines
	 */
	protected function added( $lines ) {
		$this->lines( $lines, '>' );
	}

	/**
	 * @param $lines
	 */
	protected function deleted( $lines ) {
		$this->lines( $lines, '<' );
	}

	/**
	 * @param $orig
	 * @param $closing
	 */
	protected function changed( $orig, $closing ) {
		$this->deleted( $orig );
		echo "---\n";
		$this->added( $closing );
	}
}

/**
 * A formatter that outputs unified diffs
 * @ingroup DifferenceEngine
 */
class UnifiedDiffFormatter extends DiffFormatter {
	protected $leadingContextLines = 2;
	protected $trailingContextLines = 2;

	/**
	 * @param $lines
	 */
	protected function added( $lines ) {
		$this->lines( $lines, '+' );
	}

	/**
	 * @param $lines
	 */
	protected function deleted( $lines ) {
		$this->lines( $lines, '-' );
	}

	/**
	 * @param $orig
	 * @param $closing
	 */
	protected function changed( $orig, $closing ) {
		$this->deleted( $orig );
		$this->added( $closing );
	}

	/**
	 * @param $xbeg
	 * @param $xlen
	 * @param $ybeg
	 * @param $ylen
	 * @return string
	 */
	protected function blockHeader( $xbeg, $xlen, $ybeg, $ylen ) {
		return "@@ -$xbeg,$xlen +$ybeg,$ylen @@";
	}
}

/**
 * A pseudo-formatter that just passes along the Diff::$edits array
 * @ingroup DifferenceEngine
 */
class ArrayDiffFormatter extends DiffFormatter {

	/**
	 * @param $diff
	 * @return array
	 */
	public function format( $diff ) {
		$oldline = 1;
		$newline = 1;
		$retval = array();
		foreach ( $diff->edits as $edit ) {
			switch ( $edit->type ) {
				case 'add':
					foreach ( $edit->closing as $l ) {
						$retval[] = array(
							'action' => 'add',
							'new' => $l,
							'newline' => $newline++
						);
					}
					break;
				case 'delete':
					foreach ( $edit->orig as $l ) {
						$retval[] = array(
							'action' => 'delete',
							'old' => $l,
							'oldline' => $oldline++,
						);
					}
					break;
				case 'change':
					foreach ( $edit->orig as $i => $l ) {
						$retval[] = array(
							'action' => 'change',
							'old' => $l,
							'new' => isset( $edit->closing[$i] ) ? $edit->closing[$i] : null,
							'oldline' => $oldline++,
							'newline' => $newline++,
						);
					}
					break;
				case 'copy':
					$oldline += count( $edit->orig );
					$newline += count( $edit->orig );
			}
		}
		return $retval;
	}
}

/**
 * Wikipedia table style diff formatter.
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
	 * @param $msg
	 * @return mixed
	 */
	public static function escapeWhiteSpace( $msg ) {
		$msg = preg_replace( '/^ /m', '&#160; ', $msg );
		$msg = preg_replace( '/ $/m', ' &#160;', $msg );
		$msg = preg_replace( '/  /', '&#160; ', $msg );
		return $msg;
	}

	/**
	 * @param $xbeg
	 * @param $xlen
	 * @param $ybeg
	 * @param $ylen
	 * @return string
	 */
	protected function blockHeader( $xbeg, $xlen, $ybeg, $ylen ) {
		$r = '<tr><td colspan="2" class="diff-lineno"><!--LINE ' . $xbeg . "--></td>\n" .
			'<td colspan="2" class="diff-lineno"><!--LINE ' . $ybeg . "--></td></tr>\n";
		return $r;
	}

	/**
	 * @param $header
	 */
	protected function startBlock( $header ) {
		echo $header;
	}

	protected function endBlock() {
	}

	protected function lines( $lines, $prefix = ' ', $color = 'white' ) {
	}

	/**
	 * HTML-escape parameter before calling this
	 * @param $line
	 * @return string
	 */
	protected function addedLine( $line ) {
		return $this->wrapLine( '+', 'diff-addedline', $line );
	}

	/**
	 * HTML-escape parameter before calling this
	 * @param $line
	 * @return string
	 */
	protected function deletedLine( $line ) {
		return $this->wrapLine( '−', 'diff-deletedline', $line );
	}

	/**
	 * HTML-escape parameter before calling this
	 * @param $line
	 * @return string
	 */
	protected function contextLine( $line ) {
		return $this->wrapLine( '&#160;', 'diff-context', $line );
	}

	/**
	 * @param $marker
	 * @param $class
	 * @param $line
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
	 * @param $lines array
	 */
	protected function added( $lines ) {
		foreach ( $lines as $line ) {
			echo '<tr>' . $this->emptyLine() .
				$this->addedLine( '<ins class="diffchange">' .
					htmlspecialchars( $line ) . '</ins>' ) . "</tr>\n";
		}
	}

	/**
	 * @param $lines
	 */
	protected function deleted( $lines ) {
		foreach ( $lines as $line ) {
			echo '<tr>' . $this->deletedLine( '<del class="diffchange">' .
					htmlspecialchars( $line ) . '</del>' ) .
				$this->emptyLine() . "</tr>\n";
		}
	}

	/**
	 * @param $lines
	 */
	protected function context( $lines ) {
		foreach ( $lines as $line ) {
			echo '<tr>' .
				$this->contextLine( htmlspecialchars( $line ) ) .
				$this->contextLine( htmlspecialchars( $line ) ) . "</tr>\n";
		}
	}

	/**
	 * @param $orig
	 * @param $closing
	 */
	protected function changed( $orig, $closing ) {
		wfProfileIn( __METHOD__ );

		$diff = new WordLevelDiff( $orig, $closing );
		$del = $diff->orig();
		$add = $diff->closing();

		# Notice that WordLevelDiff returns HTML-escaped output.
		# Hence, we will be calling addedLine/deletedLine without HTML-escaping.

		while ( $line = array_shift( $del ) ) {
			$aline = array_shift( $add );
			echo '<tr>' . $this->deletedLine( $line ) .
				$this->addedLine( $aline ) . "</tr>\n";
		}
		foreach ( $add as $line ) {	# If any leftovers
			echo '<tr>' . $this->emptyLine() .
				$this->addedLine( $line ) . "</tr>\n";
		}
		wfProfileOut( __METHOD__ );
	}
}
