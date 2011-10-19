<?php
/**
 * Job to update links for a given title.
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
		$parserOutput = $wgParser->parse( $revision->getText(), $this->title, $options, true, true, $revision->getId() );
		wfProfileOut( __METHOD__.'-parse' );
		wfProfileIn( __METHOD__.'-update' );
		$update = new LinksUpdate( $this->title, $parserOutput, false );
		$update->doUpdate();
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
			$parserOutput = $wgParser->parse( $revision->getText(), $title, $options, true, true, $revision->getId() );
			wfProfileOut( __METHOD__.'-parse' );
			wfProfileIn( __METHOD__.'-update' );
			$update = new LinksUpdate( $title, $parserOutput, false );
			$update->doUpdate();
			wfProfileOut( __METHOD__.'-update' );
			wfWaitForSlaves();
		}
		wfProfileOut( __METHOD__ );

		return true;
	}
}
