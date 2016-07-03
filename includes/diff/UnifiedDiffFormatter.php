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
 * A formatter that outputs unified diffs
 * @ingroup DifferenceEngine
 */
class UnifiedDiffFormatter extends DiffFormatter {

	/** @var int */
	protected $leadingContextLines = 2;

	/** @var int */
	protected $trailingContextLines = 2;

	/**
	 * @param string[] $lines
	 * @param string $prefix
	 */
	protected function lines( $lines, $prefix = ' ' ) {
		foreach ( $lines as $line ) {
			$this->writeOutput( "{$prefix}{$line}\n" );
		}
	}

	/**
	 * @param string[] $lines
	 */
	protected function added( $lines ) {
		$this->lines( $lines, '+' );
	}

	/**
	 * @param string[] $lines
	 */
	protected function deleted( $lines ) {
		$this->lines( $lines, '-' );
	}

	/**
	 * @param string[] $orig
	 * @param string[] $closing
	 */
	protected function changed( $orig, $closing ) {
		$this->deleted( $orig );
		$this->added( $closing );
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
		return "@@ -$xbeg,$xlen +$ybeg,$ylen @@";
	}

}
