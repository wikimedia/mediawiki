<?php

/**
 * @license GPL-2.0-or-later
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
