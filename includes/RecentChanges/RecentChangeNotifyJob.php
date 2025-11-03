<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use MediaWiki\JobQueue\Job;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Send an email notification.
 *
 * @ingroup JobQueue
 * @ingroup Mail
 */
class RecentChangeNotifyJob extends Job {
	private RecentChangeLookup $recentChangeLookup;

	public function __construct(
		Title $title,
		array $params,
		RecentChangeLookup $recentChangeLookup
	) {
		parent::__construct( 'enotifNotify', $title, $params );

		$this->recentChangeLookup = $recentChangeLookup;
	}

	/** @inheritDoc */
	public function run() {
		$notifier = new RecentChangeNotifier();
		// Get the user from ID (rename safe). Anons are 0, so defer to name.
		if ( isset( $this->params['editorID'] ) && $this->params['editorID'] ) {
			$editor = User::newFromId( $this->params['editorID'] );
		// B/C, only the name might be given.
		} else {
			# @todo FIXME: newFromName could return false on a badly configured wiki.
			$editor = User::newFromName( $this->params['editor'], false );
		}
		if ( !array_key_exists( 'rc_id', $this->params ) ) {
			$this->setLastError(
				'Cannot execute RecentChangeNotifyJob without `rc_id`. This has to be an old job'
			);
			return true;
		}
		$recentChange = $this->recentChangeLookup->getRecentChangeById( $this->params['rc_id'] );
		if ( $recentChange ) {
			$notifier->actuallyNotifyOnPageChange(
				$editor,
				$this->title,
				$recentChange,
				$this->params['watchers'],
				$this->params['pageStatus']
			);
		}
		return true;
	}
}

/** @deprecated class alias since 1.45 */
class_alias( RecentChangeNotifyJob::class, 'EnotifNotifyJob' );
