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
 * A pseudo-formatter that just passes along the Diff::$edits array
 * @ingroup DifferenceEngine
 */
class ArrayDiffFormatter extends DiffFormatter {

	/**
	 * @param Diff $diff A Diff object.
	 *
	 * @return array[] List of associative arrays, each describing a difference.
	 * @suppress PhanParamSignatureMismatch
	 */
	public function format( $diff ) {
		$oldline = 1;
		$newline = 1;
		$retval = [];
		foreach ( $diff->getEdits() as $edit ) {
			switch ( $edit->getType() ) {
				case 'add':
					foreach ( $edit->getClosing() as $line ) {
						$retval[] = [
							'action' => 'add',
							'new' => $line,
							'newline' => $newline++
						];
					}
					break;
				case 'delete':
					foreach ( $edit->getOrig() as $line ) {
						$retval[] = [
							'action' => 'delete',
							'old' => $line,
							'oldline' => $oldline++,
						];
					}
					break;
				case 'change':
					foreach ( $edit->getOrig() as $key => $line ) {
						$retval[] = [
							'action' => 'change',
							'old' => $line,
							'new' => $edit->getClosing( $key ),
							'oldline' => $oldline++,
							'newline' => $newline++,
						];
					}
					break;
				case 'copy':
					$oldline += count( $edit->getOrig() );
					$newline += count( $edit->getOrig() );
			}
		}

		return $retval;
	}

}
