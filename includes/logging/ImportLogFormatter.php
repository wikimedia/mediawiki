<?php
/**
 * Formatter for import log entries.
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
 * @license GPL-2.0-or-later
 * @since 1.27
 */

namespace MediaWiki\Logging;

/**
 * This class formats import log entries.
 *
 * @since 1.27
 */
class ImportLogFormatter extends LogFormatter {
	/** @inheritDoc */
	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->extractParameters();
		if ( isset( $params[3] ) ) {
			// New log items with more details
			// Messages: logentry-import-upload-details, logentry-import-interwiki-details
			$key .= '-details';
		}

		return $key;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ImportLogFormatter::class, 'ImportLogFormatter' );
