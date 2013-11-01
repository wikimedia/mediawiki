<?php
/**
 * HTML cache invalidation of all pages linking to a given title.
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
 * @ingroup Cache
 */

/**
 * Class to invalidate the HTML cache of all the pages linking to a given title.
 *
 * @ingroup Cache
 */
class HTMLCacheUpdate implements DeferrableUpdate {
	/**
	 * @var Title
	 */
	public $mTitle;

	public $mTable;

	/**
	 * @param $titleTo
	 * @param $table
	 * @param $start bool
	 * @param $end bool
	 */
	function __construct( Title $titleTo, $table ) {
		$this->mTitle = $titleTo;
		$this->mTable = $table;
	}

	public function doUpdate() {
		wfProfileIn( __METHOD__ );

		$job = new HTMLCacheUpdateJob(
			$this->mTitle,
			array(
				'table' => $this->mTable,
			) + Job::newRootJobParams( // "overall" refresh links job info
				"htmlCacheUpdate:{$this->mTable}:{$this->mTitle->getPrefixedText()}"
			)
		);

		$count = $this->mTitle->getBacklinkCache()->getNumLinks( $this->mTable, 200 );
		if ( $count >= 200 ) { // many backlinks
			JobQueueGroup::singleton()->push( $job );
			JobQueueGroup::singleton()->deduplicateRootJob( $job );
		} else { // few backlinks ($count might be off even if 0)
			$dbw = wfGetDB( DB_MASTER );
			$dbw->onTransactionIdle( function() use ( $job ) {
				$job->run(); // just do the purge query now
			} );
		}

		wfProfileOut( __METHOD__ );
	}
}
