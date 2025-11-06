<?php
/**
 * Specific methods for the patrol log.
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Rob Church <robchur@gmail.com>
 * @author Niklas Laxström
 */

namespace MediaWiki\Logging;

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\User\UserIdentity;

/**
 * Class containing static functions for working with
 * logs of patrol events
 */
class PatrolLog {

	/**
	 * Record a log event for a change being patrolled
	 *
	 * @param int|RecentChange $rc Change identifier or RecentChange object
	 * @param bool $auto Was this patrol event automatic?
	 * @param UserIdentity $user User performing the action
	 * @param string|string[]|null $tags Change tags to add to the patrol log entry
	 *   ($user should be able to add the specified tags before this is called)
	 *
	 * @return bool
	 */
	public static function record( $rc, $auto, UserIdentity $user, $tags = null ) {
		// Do not log autopatrol actions: T184485
		if ( $auto ) {
			return false;
		}

		if ( !$rc instanceof RecentChange ) {
			$rc = MediaWikiServices::getInstance()
				->getRecentChangeLookup()
				->getRecentChangeById( $rc );
			if ( !$rc ) {
				return false;
			}
		}

		$entry = new ManualLogEntry( 'patrol', 'patrol' );

		// B/C: ->getPage() on RC will return a page reference or null, reconcile this in
		//      $entry->setTarget() call so we don't throw.
		$page = $rc->getPage() ?? PageReferenceValue::localReference( NS_SPECIAL, 'Badtitle' );
		$entry->setTarget( $page );
		$entry->setParameters( self::buildParams( $rc ) );
		$entry->setPerformer( $user );
		$entry->addTags( $tags );
		$logid = $entry->insert();
		$entry->publish( $logid, 'udp' );

		return true;
	}

	/**
	 * Prepare log parameters for a patrolled change
	 *
	 * @param RecentChange $change RecentChange to represent
	 * @return array
	 */
	private static function buildParams( $change ) {
		return [
			'4::curid' => $change->getAttribute( 'rc_this_oldid' ),
			'5::previd' => $change->getAttribute( 'rc_last_oldid' ),
			'6::auto' => 0
		];
	}
}

/** @deprecated class alias since 1.44 */
class_alias( PatrolLog::class, 'PatrolLog' );
