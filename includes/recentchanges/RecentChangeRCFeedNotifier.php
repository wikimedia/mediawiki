<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\RCFeed\RCFeed;

/**
 * @since 1.45
 */
class RecentChangeRCFeedNotifier {

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RCFeeds,
		MainConfigNames::UseRCPatrol,
		MainConfigNames::UseNPPatrol,
		MainConfigNames::CanonicalServer,
		MainConfigNames::Script,
	];

	private HookContainer $hookContainer;
	private ServiceOptions $options;

	public function __construct(
		HookContainer $hookContainer,
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->hookContainer = $hookContainer;
		$this->options = $options;
	}

	/**
	 * Notify all the feeds about the change.
	 *
	 * @param RecentChange $recentChange
	 * @param array|null $feeds Optional feeds to send to, defaults to $wgRCFeeds
	 */
	public function notifyRCFeeds( RecentChange $recentChange, ?array $feeds = null ) {
		// T403757: Don't send 'suppressed from creation' recent changes entries to the RCFeeds as they do not
		// have systems to appropriately redact suppressed / deleted material
		if ( $recentChange->getAttribute( 'rc_deleted' ) != 0 ) {
			return;
		}

		$feeds ??= $this->options->get( MainConfigNames::RCFeeds );

		$performer = $recentChange->getPerformerIdentity();

		foreach ( $feeds as $params ) {
			$params += [
				'omit_bots' => false,
				'omit_anon' => false,
				'omit_user' => false,
				'omit_minor' => false,
				'omit_patrolled' => false,
			];

			if (
				( $params['omit_bots'] && $recentChange->getAttribute( 'rc_bot' ) ) ||
				( $params['omit_anon'] && !$performer->isRegistered() ) ||
				( $params['omit_user'] && $performer->isRegistered() ) ||
				( $params['omit_minor'] && $recentChange->getAttribute( 'rc_minor' ) ) ||
				( $params['omit_patrolled'] && $recentChange->getAttribute( 'rc_patrolled' ) ) ||
				$recentChange->getAttribute( 'rc_type' ) == RC_EXTERNAL
			) {
				continue;
			}

			$actionComment = $recentChange->getExtra( 'actionCommentIRC' );

			$feed = RCFeed::factory( $params );
			$feed->notify( $recentChange, $actionComment );
		}
	}

	/**
	 * Get the extra URL that is given as part of the notification to RCFeed consumers.
	 *
	 * This is mainly to facilitate patrolling or other content review.
	 *
	 * @param RecentChange $recentChange
	 * @return string|null URL
	 */
	public function getNotifyUrl( RecentChange $recentChange ): ?string {
		$useRCPatrol = $this->options->get( MainConfigNames::UseRCPatrol );
		$useNPPatrol = $this->options->get( MainConfigNames::UseNPPatrol );
		$canonicalServer = $this->options->get( MainConfigNames::CanonicalServer );
		$script = $this->options->get( MainConfigNames::Script );

		$source = $recentChange->getAttribute( 'rc_source' );
		if ( $source == RecentChange::SRC_LOG ) {
			$url = null;
		} else {
			$url = $canonicalServer . $script;
			if ( $source == RecentChange::SRC_NEW ) {
				$query = '?oldid=' . $recentChange->getAttribute( 'rc_this_oldid' );
			} else {
				$query = '?diff=' . $recentChange->getAttribute( 'rc_this_oldid' )
					. '&oldid=' . $recentChange->getAttribute( 'rc_last_oldid' );
			}
			if ( $useRCPatrol ||
				( $recentChange->getAttribute( 'rc_source' ) == RecentChange::SRC_NEW && $useNPPatrol )
			) {
				$query .= '&rcid=' . $recentChange->getAttribute( 'rc_id' );
			}

			( new HookRunner( $this->hookContainer ) )->onIRCLineURL( $url, $query, $recentChange );
			$url .= $query;
		}

		return $url;
	}

}
