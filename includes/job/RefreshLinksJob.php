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

		$revision = Revision::newFromTitle( $this->title );
		if ( !$revision ) {
			$this->error = 'refreshLinks: Article not found "' . $this->title->getPrefixedDBkey() . '"';
			wfProfileOut( __METHOD__ );
			return false;
		}

		wfProfileIn( __METHOD__.'-parse' );
		$options = ParserOptions::newFromUserAndLang( new User, $wgContLang );
		$content = $revision->getContent();
		$parserOutput = $content->getParserOutput( $this->title, $revision->getId(), $options, false );
		wfProfileOut( __METHOD__.'-parse' );
		wfProfileIn( __METHOD__.'-update' );

		$updates = $content->getSecondaryDataUpdates( $this->title, null, false, $parserOutput  );
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
			$table, $this->params['start'], $this->params['end']);
		
		# Not suitable for page load triggered job running!
		# Gracefully switch to refreshLinks jobs if this happens.
		if( php_sapi_name() != 'cli' ) {
			$jobs = array();
			foreach ( $titles as $title ) {
				$jobs[] = new RefreshLinksJob( $title, '' );
			}
			Job::batchInsert( $jobs );

			wfProfileOut( __METHOD__ );
			return true;
		}
		$options = ParserOptions::newFromUserAndLang( new User, $wgContLang );
		# Re-parse each page that transcludes this page and update their tracking links...
		foreach ( $titles as $title ) {
			$revision = Revision::newFromTitle( $title );
			if ( !$revision ) {
				$this->error = 'refreshLinks: Article not found "' . $title->getPrefixedDBkey() . '"';
				wfProfileOut( __METHOD__ );
				return false;
			}
			wfProfileIn( __METHOD__.'-parse' );
			$options = ParserOptions::newFromUserAndLang( new User, $wgContLang );
			$content = $revision->getContent();
			$parserOutput = $content->getParserOutput( $title, $revision->getId(), $options, false );
			wfProfileOut( __METHOD__.'-parse' );
			wfProfileIn( __METHOD__.'-update' );

			$updates = $content->getSecondaryDataUpdates( $title, null, false, $parserOutput  );
			DataUpdate::runUpdates( $updates );

			wfProfileOut( __METHOD__.'-update' );
			wfWaitForSlaves();
		}
		wfProfileOut( __METHOD__ );

		return true;
	}
}
