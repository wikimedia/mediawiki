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
