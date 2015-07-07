<?php
/**
 * Formatter for protect log entries.
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
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.26
 */

/**
 * This class formats protect log entries.
 *
 * @since 1.26
 */
class ProtectLogFormatter extends LogFormatter {
	public function getPreloadTitles() {
		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'move_prot' ) {
			$params = $this->extractParameters();
			return array( Title::newFromText( $params[3] ) );
		}
		return array();
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'move_prot' ) {
			$oldname = $this->makePageLink( Title::newFromText( $params[3] ), array( 'redirect' => 'no' ) );
			$params[3] = Message::rawParam( $oldname );
		}

		return $params;
	}

	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		static $map = array(
			// param keys for move_prot sub type
			'4:title:oldtitle',
			'4::oldtitle' => '4:title:oldtitle',
		);
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		return $params;
	}
}
