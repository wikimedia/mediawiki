<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\Deferred\CdnCacheUpdate;
use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;

/**
 * Job to purge a set of URLs from CDN
 *
 * @since 1.27
 * @ingroup JobQueue
 */
class CdnPurgeJob extends Job implements GenericParameterJob {
	public function __construct( array $params ) {
		parent::__construct( 'cdnPurge', $params );
		$this->removeDuplicates = false; // delay semantics are critical
	}

	/** @inheritDoc */
	public function run() {
		// Use purge() directly to avoid infinite recursion
		CdnCacheUpdate::purge( $this->params['urls'] );

		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( CdnPurgeJob::class, 'CdnPurgeJob' );
