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
 *
 * @file
 */

namespace MediaWiki\RCFeed;

use MediaWiki\Json\FormatJson;

/**
 * Format a recent change notification using JSON (https://www.json.org).
 *
 * Parameters:
 * - `channel`: If set, a 'channel' property with the same value will
 *   also be added to the JSON-formatted message.
 *
 * @see $wgRCFeeds
 * @since 1.22
 * @ingroup RecentChanges
 */
class JSONRCFeedFormatter extends MachineReadableRCFeedFormatter {

	/** @inheritDoc */
	protected function formatArray( array $packet ) {
		return FormatJson::encode( $packet );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( JSONRCFeedFormatter::class, 'JSONRCFeedFormatter' );
