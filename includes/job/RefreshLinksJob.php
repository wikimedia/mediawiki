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
		global $wgParser, $wgContLang;
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

		$revision = Revision::newFromTitle( $this->title, 0, Revision::AVOID_MASTER );
		if ( !$revision ) {
			$this->error = 'refreshLinks: Article not found "' .
				$this->title->getPrefixedDBkey() . '"';
			wfProfileOut( __METHOD__ );
			return false;
		}

		wfProfileIn( __METHOD__.'-parse' );
		$options = ParserOptions::newFromUserAndLang( new User, $wgContLang );
		$parserOutput = $wgParser->parse(
			$revision->getText(), $this->title, $options, true, true, $revision->getId() );
		wfProfileOut( __METHOD__.'-parse' );
		wfProfileIn( __METHOD__.'-update' );

		$updates = $parserOutput->getSecondaryDataUpdates( $this->title, false );
		DataUpdate::runUpdates( $updates );

		wfProfileOut( __METHOD__.'-update' );
		wfProfileOut( __METHOD__ );
		return true;
	}
}

/**
 * Background job to update links for a given title.
 * Newer version for high use templates.
 *
 * @ingroup JobQueue
 */
class RefreshLinksJob2 extends Job {

	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'refreshLinks2', $title, $params, $id );
	}

	/**
	 * Run a refreshLinks2 job
	 * @return boolean success
	 */
	function run() {
		global $wgParser, $wgContLang;

		wfProfileIn( __METHOD__ );

		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		if( is_null( $this->title ) ) {
			$this->error = "refreshLinks2: Invalid title";
			wfProfileOut( __METHOD__ );
			return false;
		}
		if( !isset($this->params['start']) || !isset($this->params['end']) ) {
			$this->error = "refreshLinks2: Invalid params";
			wfProfileOut( __METHOD__ );
			return false;
		}
		// Back compat for pre-r94435 jobs
		$table = isset( $this->params['table'] ) ? $this->params['table'] : 'templatelinks';
		$titles = $this->title->getBacklinkCache()->getLinks(
			$table, $this->params['start'], $this->params['end'] );

		$jobs = array();
		foreach ( $titles as $title ) {
			// Avoid slave lag when fetching templates
			$params = ( wfGetLB()->getServerCount() > 1 )
				? array( 'masterPos' => wfGetLB()->getMasterPos() )
				: '';
			$jobs[] = new RefreshLinksJob( $title, $params );
		}
		Job::batchInsert( $jobs );

		wfProfileOut( __METHOD__ );
		return true;
	}
}
