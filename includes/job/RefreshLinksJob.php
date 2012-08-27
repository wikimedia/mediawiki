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
	}

	/**
	 * Run a refreshLinks job
	 * @return boolean success
	 */
	function run() {
		wfProfileIn( __METHOD__ );

		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		if ( is_null( $this->title ) ) {
			$this->error = "refreshLinks: Invalid title";
			wfProfileOut( __METHOD__ );
			return false;
		}

		# Wait for the DB of the current/next slave DB handle to catch up to the master.
		# This way, we get the correct page_latest for templates or files that just changed
		# milliseconds ago, having triggered this job to begin with.
		if ( isset( $this->params['masterPos'] ) ) {
			wfGetLB()->waitFor( $this->params['masterPos'] );
		}

		$revision = Revision::newFromTitle( $this->title, false, Revision::READ_NORMAL );
		if ( !$revision ) {
			$this->error = 'refreshLinks: Article not found "' .
				$this->title->getPrefixedDBkey() . '"';
			wfProfileOut( __METHOD__ );
			return false; // XXX: what if it was just deleted?
		}

		self::runForTitleInternal( $this->title, $revision, __METHOD__ );

		wfProfileOut( __METHOD__ );
		return true;
	}

	public static function runForTitleInternal( Title $title, Revision $revision, $fname ) {
		global $wgParser, $wgContLang;

		wfProfileIn( $fname . '-parse' );
		$options = ParserOptions::newFromUserAndLang( new User, $wgContLang );
		$parserOutput = $wgParser->parse(
			$revision->getText(), $title, $options, true, true, $revision->getId() );
		wfProfileOut( $fname . '-parse' );

		wfProfileIn( $fname . '-update' );
		$updates = $parserOutput->getSecondaryDataUpdates( $title, false );
		DataUpdate::runUpdates( $updates );
		wfProfileOut( $fname . '-update' );
	}
}

/**
 * Background job to update links for a given title.
 * Newer version for high use templates.
 *
 * @ingroup JobQueue
 */
class RefreshLinksJob2 extends Job {
	const MAX_TITLES_RUN = 10;

	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'refreshLinks2', $title, $params, $id );
	}

	/**
	 * Run a refreshLinks2 job
	 * @return boolean success
	 */
	function run() {
		wfProfileIn( __METHOD__ );

		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		if ( is_null( $this->title ) ) {
			$this->error = "refreshLinks2: Invalid title";
			wfProfileOut( __METHOD__ );
			return false;
		} elseif ( !isset( $this->params['start'] ) || !isset( $this->params['end'] ) ) {
			$this->error = "refreshLinks2: Invalid params";
			wfProfileOut( __METHOD__ );
			return false;
		}

		// Back compat for pre-r94435 jobs
		$table = isset( $this->params['table'] ) ? $this->params['table'] : 'templatelinks';

		// Avoid slave lag when fetching templates
		if ( isset( $this->params['masterPos'] ) ) {
			$masterPos = $this->params['masterPos'];
		} elseif ( wfGetLB()->getServerCount() > 1  ) {
			$masterPos = wfGetLB()->getMasterPos();
		} else {
			$masterPos = false;
		}

		$titles = $this->title->getBacklinkCache()->getLinks(
			$table, $this->params['start'], $this->params['end'] );

		if ( $titles->count() > self::MAX_TITLES_RUN ) {
			# We don't want to parse too many pages per job as it can starve other jobs.
			# If there are too many pages to parse, break this up into smaller jobs. By passing
			# in the master position here we can cut down on the time spent waiting for slaves to
			# catch up by the runners handling these jobs since time will have passed between now
			# and when they pop these jobs off the queue.
			$start = 0; // batch start
			$end   = 0; // batch end
			$bsize = 0; // batch size
			$first = true; // first of batch
			$jobs  = array();
			foreach ( $titles as $title ) {
				$start = $first ? $title->getArticleId() : $start;
				$end   = $title->getArticleId();
				$first = false;
				if ( ++$bsize >= self::MAX_TITLES_RUN ) {
					$jobs[] = new RefreshLinksJob2( $this->title, array(
						'table'     => $table,
						'start'     => $start,
						'end'       => $end,
						'masterPos' => $masterPos
					) );
					$first = true;
					$start = $end = $bsize = 0;
				}
			}
			if ( $bsize > 0 ) { // group remaining pages into a job
				$jobs[] = new RefreshLinksJob2( $this->title, array(
					'table'     => $table,
					'start'     => $start,
					'end'       => $end,
					'masterPos' => $masterPos
				) );
			}
			Job::batchInsert( $jobs );
		} elseif ( php_sapi_name() != 'cli' ) {
			# Not suitable for page load triggered job running!
			# Gracefully switch to refreshLinks jobs if this happens.
			$jobs = array();
			foreach ( $titles as $title ) {
				$jobs[] = new RefreshLinksJob( $title, array( 'masterPos' => $masterPos ) );
			}
			Job::batchInsert( $jobs );
		} else {
			# Wait for the DB of the current/next slave DB handle to catch up to the master.
			# This way, we get the correct page_latest for templates or files that just changed
			# milliseconds ago, having triggered this job to begin with.
			if ( $masterPos ) {
				wfGetLB()->waitFor( $masterPos );
			}
			# Re-parse each page that transcludes this page and update their tracking links...
			foreach ( $titles as $title ) {
				$revision = Revision::newFromTitle( $title, false, Revision::READ_NORMAL );
				if ( !$revision ) {
					$this->error = 'refreshLinks: Article not found "' .
						$title->getPrefixedDBkey() . '"';
					continue; // skip this page
				}
				RefreshLinksJob::runForTitleInternal( $title, $revision, __METHOD__ );
				wfWaitForSlaves();
			}
		}

		wfProfileOut( __METHOD__ );
		return true;
	}
}
