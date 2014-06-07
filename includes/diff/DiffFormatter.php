<?php
/**
 * Base for diff rendering classes. Portions taken from phpwiki-1.3.3.
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

	/** @var int Number of leading context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses
	 * may want to set this to other values.
	 */
	protected $leadingContextLines = 0;

	/** @var int Number of trailing context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses
	 * may want to set this to other values.
	 */
	protected $trailingContextLines = 0;

	/**
	 * Format a diff.
	 *
	 * @param Diff $diff A Diff object.
	 *
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

		// Initialize $x0 and $y0 to prevent IDEs from getting confused.
		$x0 = $y0 = 0;
		foreach ( $diff->edits as $edit ) {
			if ( $edit->type == 'copy' ) {
				if ( is_array( $block ) ) {
					if ( count( $edit->orig ) <= $nlead + $ntrail ) {
						$block[] = $edit;
					} else {
						if ( $ntrail ) {
							$context = array_slice( $edit->orig, 0, $ntrail );
							$block[] = new DiffOpCopy( $context );
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
						$block[] = new DiffOpCopy( $context );
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
	 * @param int $xbeg
	 * @param int $xlen
	 * @param int $ybeg
	 * @param int $ylen
	 * @param $edits
	 *
	 * @throws MWException If the edit type is not known.
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
	 * @param int $xbeg
	 * @param int $xlen
	 * @param int $ybeg
	 * @param int $ylen
	 *
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

	/**
	 * Called at the start of a block of connected edits.
	 * This default implementation writes the header and a newline to the output buffer.
	 *
	 * @param string $header
	 */
	protected function startBlock( $header ) {
		echo $header . "\n";
	}

	/**
	 * Called at the end of a block of connected edits.
	 * This default implementation does nothing.
	 */
	protected function endBlock() {
	}

	/**
	 * Writes all (optionally prefixed) lines to the output buffer, separated by newlines.
	 *
	 * @param string[] $lines
	 * @param string $prefix
	 */
	protected function lines( $lines, $prefix = ' ' ) {
		foreach ( $lines as $line ) {
			echo "$prefix $line\n";
		}
	}

	/**
	 * @param string[] $lines
	 */
	protected function context( $lines ) {
		$this->lines( $lines );
	}

	/**
	 * @param string[] $lines
	 */
	protected function added( $lines ) {
		$this->lines( $lines, '>' );
	}

	/**
	 * @param string[] $lines
	 */
	protected function deleted( $lines ) {
		$this->lines( $lines, '<' );
	}

	/**
	 * Writes the two sets of lines to the output buffer, separated by "---" and a newline.
	 *
	 * @param string[] $orig
	 * @param string[] $closing
	 */
	protected function changed( $orig, $closing ) {
		$this->deleted( $orig );
		echo "---\n";
		$this->added( $closing );
	}

}
