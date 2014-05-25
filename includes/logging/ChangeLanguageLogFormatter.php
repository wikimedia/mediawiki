<?php
/**
 * Formatter for changelang log entries.
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
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.22
 */

/**
 * This class formats language change log entries.
 *
 * @since 1.24
 */
class ChangeLangLogFormatter extends LogFormatter {
	protected function getMessageKey() {
		$key = parent::getMessageKey();
		wfDebugLog( 'test', "$key");

		return $key;
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$subtype = $this->entry->getSubtype();
		$p = count($params);
		wfDebugLog('text33', "$p");
/*		if ( in_array( $subtype, array( 'event', 'revision' ) ) ) {
			// $params[3] here is 'revision' or 'archive' for page revisions, 'oldimage' or
			// 'filearchive' for file versions, or a comma-separated list of log_ids for log
			// entries. $subtype here is 'revision' for page revisions and file
			// versions, or 'event' for log entries.
			if ( ( $subtype === 'event' && count( $params ) === 6 )
				|| ( $subtype === 'revision' && isset( $params[3] )
					&& ( $params[3] === 'revision' || $params[3] === 'oldimage'
						|| $params[3] === 'archive' || $params[3] === 'filearchive' )
				)
			) {
				$paramStart = $subtype === 'revision' ? 4 : 3;

				$old = $this->parseBitField( $params[$paramStart + 1] );
				$new = $this->parseBitField( $params[$paramStart + 2] );
				list( $hid, $unhid, $extra ) = RevisionDeleter::getChanges( $new, $old );
				$changes = array();
				// messages used: revdelete-content-hid, revdelete-summary-hid, revdelete-uname-hid
				foreach ( $hid as $v ) {
					$changes[] = $this->msg( "$v-hid" )->plain();
				}
				// messages used: revdelete-content-unhid, revdelete-summary-unhid, revdelete-uname-unhid
				foreach ( $unhid as $v ) {
					$changes[] = $this->msg( "$v-unhid" )->plain();
				}
				foreach ( $extra as $v ) {
					$changes[] = $this->msg( $v )->plain();
				}
				$changeText = $this->context->getLanguage()->listToText( $changes );

				$newParams = array_slice( $params, 0, 3 );
				$newParams[3] = $changeText;
				$count = count( explode( ',', $params[$paramStart] ) );
				$newParams[4] = $this->context->getLanguage()->formatNum( $count );

				$this->parsedParametersDeleteLog = $newParams;
				return $this->parsedParametersDeleteLog;
			} else {
				$this->parsedParametersDeleteLog = array_slice( $params, 0, 3 );
				return $this->parsedParametersDeleteLog;
			}
		}

		$this->parsedParametersDeleteLog = $params;
	return $this->parsedParametersDeleteLog;
-*/	}
}
