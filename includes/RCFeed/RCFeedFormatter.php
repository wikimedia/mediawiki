<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RCFeed;

use MediaWiki\RecentChanges\RecentChange;

/**
 * Interface for RC feed formatters
 *
 * @stable to implement
 * @since 1.22
 * @ingroup RecentChanges
 */
interface RCFeedFormatter {
	/**
	 * Formats the line to be sent by an engine
	 *
	 * @param array $feed The feed, as configured in an associative array.
	 * @param RecentChange $rc The RecentChange object showing what sort
	 *                         of event has taken place.
	 * @param string|null $actionComment
	 * @return string|null The text to send.  If the formatter returns null,
	 *  the line will not be sent.
	 */
	public function getLine( array $feed, RecentChange $rc, $actionComment );
}
/** @deprecated class alias since 1.43 */
class_alias( RCFeedFormatter::class, 'RCFeedFormatter' );
