<?php
/**
 * Formatter for user group rights log entries.
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
 * @author Alexander Monk (based on RightsLogFormatter by Alexandre Emsenhuber)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.22
 */

/**
 * This class formats group rights log entries.
 *
 * @since 1.22
 */
class GroupRightsLogFormatter extends LogFormatter {
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		$oldRights = $params[3];
		$newRights = $params[4];

		if ( $oldRights === '' ) {
			$oldRights = array();
		} elseif ( is_string( $oldRights ) ) {
			$oldRights = array_map( 'trim', explode( ',', $oldRights ) );
		}
		if ( $newRights === '' ) {
			$newRights = array();
		} elseif ( is_string( $newRights ) ) {
			$newRights = array_map( 'trim', explode( ',', $newRights ) );
		}

		$added = array_diff( $newRights, $oldRights );
		$removed = array_diff( $oldRights, $newRights );

		$lang = $this->context->getLanguage();
		if ( count( $added ) ) {
			$params[3] = $lang->listToText( array_values( $added ) );
		} else {
			$params[3] = $this->msg( 'rightsnone' )->text();
		}
		if ( count( $removed ) ) {
			$params[4] = $lang->listToText( array_values( $removed ) );
		} else {
			$params[4] = $this->msg( 'rightsnone' )->text();
		}

		return $params;
	}
}
