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
 */

/**
 * This class formats tag log entries.
 *
 * Parameters (one-based indexes):
 * 4::revid
 * 5::logid
 * 6:list:tagsAdded
 * 7:number:tagsAddedCount
 * 8:list:tagsRemoved
 * 9:number:tagsRemovedCount
 *
 * @since 1.25
 */
class TagLogFormatter extends LogFormatter {
	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->getMessageParameters();

		$add = ( isset( $params[6] ) && isset( $params[6]['num'] ) && $params[6]['num'] );
		$remove = ( isset( $params[8] ) && isset( $params[8]['num'] ) && $params[8]['num'] );
		$key .= ( $remove ? ( $add ? '' : '-remove' ) : '-add' );

		if ( isset( $params[4] ) && $params[4] ) {
			$key .= '-logentry';
		} else {
			$key .= '-revision';
		}

		return $key;
	}
}
