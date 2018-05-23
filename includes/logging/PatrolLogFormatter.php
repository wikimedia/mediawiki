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
 * This class formats patrol log entries.
 *
 * @since 1.19
 */
class PatrolLogFormatter extends LogFormatter {
	protected function getMessageKey() {
		$params = $this->getMessageParameters();
		if ( isset( $params[5] ) && $params[5] ) {
			$key = 'logentry-patrol-patrol-auto';
		} else {
			$key = 'logentry-patrol-patrol';
		}

		return $key;
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		$target = $this->entry->getTarget();
		$oldid = $params[3];
		$revision = $this->context->getLanguage()->formatNum( $oldid, true );

		if ( $this->plaintext ) {
			$revlink = $revision;
		} elseif ( $target->exists() ) {
			$query = [
				'oldid' => $oldid,
				'diff' => 'prev'
			];
			$revlink = $this->getLinkRenderer()->makeLink( $target, $revision, [], $query );
		} else {
			$revlink = htmlspecialchars( $revision );
		}

		$params[3] = Message::rawParam( $revlink );

		return $params;
	}

	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		static $map = [
			'4:number:curid',
			'5:number:previd',
			'6:bool:auto',
			'4::curid' => '4:number:curid',
			'5::previd' => '5:number:previd',
			'6::auto' => '6:bool:auto',
		];
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		return $params;
	}
}
