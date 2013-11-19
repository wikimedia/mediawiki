<?php
/**
 * Job to update links for a given title.
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
 * @ingroup JobQueue
 */

/**
 * Background job to update links for a given title.
 *
 * @ingroup JobQueue
 */
class RefreshLinksJob extends Job {
	function __construct( $title, $params = '', $id = 0 ) {
		parent::__construct( 'refreshLinks', $title, $params, $id );
		$this->removeDuplicates = true; // job is expensive
	}

	/**
	 * Run a refreshLinks job
	 * @return boolean success
	 */
	function run() {
		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		if ( is_null( $this->title ) ) {
			$this->error = "refreshLinks: Invalid title";
			return false;
		}

		# Wait for the DB of the current/next slave DB handle to catch up to the master.
		# This way, we get the correct page_latest for templates or files that just changed
		# milliseconds ago, having triggered this job to begin with.
		if ( isset( $this->params['masterPos'] ) && $this->params['masterPos'] !== false ) {
			wfGetLB()->waitFor( $this->params['masterPos'] );
		}

		$revision = Revision::newFromTitle( $this->title, false, Revision::READ_NORMAL );
		if ( !$revision ) {
			$this->error = 'refreshLinks: Article not found "' .
				$this->title->getPrefixedDBkey() . '"';
			return false; // XXX: what if it was just deleted?
		}

		self::runForTitleInternal( $this->title, $revision, __METHOD__ );

		return true;
	}

	/**
	 * @return Array
	 */
	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		// Don't let highly unique "masterPos" values ruin duplicate detection
		if ( is_array( $info['params'] ) ) {
			unset( $info['params']['masterPos'] );
		}
		return $info;
	}

	/**
	 * @param $title Title
	 * @param $revision Revision
	 * @param $fname string
	 * @return void
	 */
	public static function runForTitleInternal( Title $title, Revision $revision, $fname ) {
		wfProfileIn( $fname );
		$content = $revision->getContent( Revision::RAW );

		if ( !$content ) {
			// if there is no content, pretend the content is empty
			$content = $revision->getContentHandler()->makeEmptyContent();
		}

		// Revision ID must be passed to the parser output to get revision variables correct
		$parserOutput = $content->getParserOutput( $title, $revision->getId(), null, false );

		$updates = $content->getSecondaryDataUpdates( $title, null, false, $parserOutput );
		DataUpdate::runUpdates( $updates );

		InfoAction::invalidateCache( $title );

		wfProfileOut( $fname );
	}
}
