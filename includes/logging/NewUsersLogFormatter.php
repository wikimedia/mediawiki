<?php
/**
 * Formatter for new user log entries.
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
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 * @since 1.22
 */

/**
 * This class formats new user log entries.
 *
 * @since 1.19
 */
class NewUsersLogFormatter extends LogFormatter {
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'create2' || $subtype === 'byemail' ) {
			if ( isset( $params[3] ) ) {
				$target = User::newFromId( $params[3] );
			} else {
				$target = User::newFromName( $this->entry->getTarget()->getText(), false );
			}
			$params[2] = Message::rawParam( $this->makeUserLink( $target ) );
			$params[3] = $target->getName();
		}

		return $params;
	}

	public function getComment() {
		$timestamp = wfTimestamp( TS_MW, $this->entry->getTimestamp() );
		if ( $timestamp < '20080129000000' ) {
			# Suppress $comment from old entries (before 2008-01-29),
			# not needed and can contain incorrect links
			return '';
		}

		return parent::getComment();
	}

	public function getPreloadTitles() {
		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'create2' || $subtype === 'byemail' ) {
			// add the user talk to LinkBatch for the userLink
			return [ Title::makeTitle( NS_USER_TALK, $this->entry->getTarget()->getText() ) ];
		}

		return [];
	}
}
