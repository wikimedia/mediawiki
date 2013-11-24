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
