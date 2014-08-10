<?php
/**
 * Formatter for block log entries.
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
 * @since 1.25
 */

/**
 * This class formats block log entries.
 *
 * @todo Fix this to properly extend LogFormatter
 * @since 1.25
 */
class BlockLogFormatter extends LegacyLogFormatter {

	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		static $map = array(
			'4::duration',
			'5:array:flags',
			'5::flags' => '5:array:flags',
		);
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		if ( isset( $params['5:array:flags'] ) && !is_array( $params['5:array:flags'] ) ) {
			$params['5:array:flags'] = $params['5:array:flags'] === ''
				? array()
				: explode( ',', $params['5:array:flags'] );
		}

		if ( isset( $params['4::duration'] ) &&
			SpecialBlock::parseExpiryInput( $params['4::duration'] ) !== wfGetDB( DB_SLAVE )->getInfinity()
		) {
			$ts = wfTimestamp( TS_UNIX, $entry->getTimestamp() );
			$params[':timestamp:expiry'] = strtotime( $params['4::duration'], $ts );
		}

		return $params;
	}

	public function formatParametersForApi() {
		$ret = parent::formatParametersForApi();
		if ( isset( $ret['flags'] ) ) {
			ApiResult::setIndexedTagName( $ret['flags'], 'f' );
		}
		return $ret;
	}

}
