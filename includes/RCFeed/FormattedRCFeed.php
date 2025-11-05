<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RCFeed;

use MediaWiki\RecentChanges\RecentChange;

/**
 * Base class for RCFeed implementations that use RCFeedFormatter.
 *
 * Parameters:
 *  - formatter: [required] Which RCFeedFormatter class to use.
 *
 * @see $wgRCFeeds
 * @since 1.29
 * @ingroup RecentChanges
 */
abstract class FormattedRCFeed extends RCFeed {
	private array $params;

	public function __construct( array $params ) {
		$this->params = $params;
	}

	/**
	 * Send some text to the specified feed.
	 *
	 * @param array $feed The feed, as configured in an associative array
	 * @param string $line The text to send
	 * @return bool Success
	 */
	abstract public function send( array $feed, $line );

	/**
	 * @param RecentChange $rc
	 * @param string|null $actionComment
	 * @return bool Success
	 */
	public function notify( RecentChange $rc, $actionComment = null ) {
		$params = $this->params;
		/** @var RCFeedFormatter $formatter */
		$formatter = is_object( $params['formatter'] ) ? $params['formatter'] : new $params['formatter'];

		$line = $formatter->getLine( $params, $rc, $actionComment );
		if ( !$line ) {
			// @codeCoverageIgnoreStart
			// T109544 - If a feed formatter returns null, this will otherwise cause an
			// error in at least RedisPubSubFeedEngine. Not sure best to handle this.
			// @phan-suppress-next-line PhanTypeMismatchReturnProbablyReal
			return;
			// @codeCoverageIgnoreEnd
		}
		return $this->send( $params, $line );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( FormattedRCFeed::class, 'FormattedRCFeed' );
