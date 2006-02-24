<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "This file is part of MediaWiki, it is not a valid entry point\n" );
}

class Job {
	var $command,
		$title,
		$params, 
		$removeDuplicates, 
		$error;

	/*-------------------------------------------------------------------------
	 * Static functions
	 *------------------------------------------------------------------------*/
	/**
	 * Add an array of refreshLinks jobs to the queue
	 * @param array $titles Array of title objects. 
	 * @static
	 */
	function queueLinksJobs( $titles ) {
		$fname = 'Job::queueLinksJobs';
		wfProfileIn( $fname );
		foreach ( $titles as $title ) {
			$job = new Job( 'refreshLinks', $title );
			$job->insert();
		}
		wfProfileOut( $fname );
	}

	/**
	 * Pop a job off the front of the queue
	 * @static
	 * @return Job or false if there's no jobs
	 */
	function pop() {
		$fname = 'Job::pop';
		wfProfileIn( $fname );

		// First check to see if there are any jobs in the slave DB
		$dbr =& wfGetDB( DB_SLAVE );
		$id = $dbr->selectField( 'job', 'job_id', '', $fname, array( 'LIMIT' => 1 ) );
		if ( $id === false ) {
			wfProfileOut( $fname );
			return false;
		}

		// Pop an item off the front of the queue
		// Method due to Domas, may not work on all DBMSes
		$dbw =& wfGetDB( DB_MASTER );
		$jobTable = $dbw->tableName( 'job' );
		$dbw->query( "DELETE FROM $jobTable WHERE " .
			'(job_cmd = @job_cmd := job_cmd) AND ' .
			'(job_namespace = @job_namespace := job_namespace) AND ' .
			'(job_title = @job_title := job_title) AND ' .
			'(job_params = @job_params := job_params) ' .
			'ORDER BY job_id LIMIT 1', $fname );
		$affected = $dbw->affectedRows();
		// Commit now before 100 other threads pile up behind us
		$dbw->immediateCommit();
		if ( !$affected ) {
			wfProfileOut( $fname );
			return false;
		}

		$res = $dbw->query( "SELECT @job_cmd, @job_namespace, @job_title, @job_params", $fname );
		$row = $dbw->fetchRow( $res );
		if ( !$row ) {
			wfProfileOut( $fname );
			return false;
		}

		$command = $row['@job_cmd'];
		$namespace = $row['@job_namespace'];
		$dbkey = $row['@job_title'];
		$title = Title::makeTitleSafe( $namespace, $dbkey );
		$params = $row['@job_params'];
		$job = new Job( $command, $title, $params );
		wfProfileOut( $fname );
		return $job;
	}

	/*-------------------------------------------------------------------------
	 * Non-static functions
	 *------------------------------------------------------------------------*/

	function Job( $command, $title, $params = '' ) {
		$this->command = $command;
		$this->title = $title;
		$this->params = $params;

		// A bit of premature generalisation
		// Oh well, the whole class is premature generalisation really
		$this->removeDuplicates = true;
	}

	function insert() {
		$fname = 'Job::insert';

		$fields = array(
			'job_cmd' => $this->command,
			'job_namespace' => $this->title->getNamespace(),
			'job_title' => $this->title->getDBkey(),
			'job_params' => $this->params
		);

		$dbw =& wfGetDB( DB_MASTER );
		
		if ( $this->removeDuplicates ) {
			$dbw->delete( 'job', $fields, $fname );
		}
		$fields['job_id'] = $dbw->nextSequenceValue( 'job_job_id_seq' );
		$dbw->insert( 'job', $fields, $fname );
	}

	/**
	 * Run the job
	 * @return boolean success
	 */
	function run() {
		$fname = 'Job::run';
		wfProfileIn( $fname );
		switch ( $this->command ) {
			case 'refreshLinks':
				$retval = $this->refreshLinks();
				break;
			default:
				$this->error = "Invalid job type {$this->command}, ignoring";
				wfDebug( $this->error . "\n" );
				$retval = false;
		}
		wfProfileOut( $fname );
		return $retval;
	}

	/**
	 * Run a refreshLinks job
	 * @return boolean success
	 */
	function refreshLinks() {
		global $wgParser;
		
		$dbw =& wfGetDB( DB_MASTER );

		$linkCache =& LinkCache::singleton();
		$linkCache->clear();
		
		if ( is_null( $this->title ) ) {
			$this->error = "refreshLinks: Invalid title";
			return false;
		}

		$revision = Revision::newFromTitle( $this->title );
		if ( !$revision ) {
			$this->error = 'refreshLinks: Article not found "' . $this->title->getPrefixedDBkey() . '"';
			return false;
		}

		$options = new ParserOptions;
		$parserOutput = $wgParser->parse( $revision->getText(), $this->title, $options, true, true, $revision->getId() );
		$update = new LinksUpdate( $this->title, $parserOutput );
		$update->doUpdate();
		return true;
	}

	function toString() {
		if ( is_object( $this->title ) ) {
			$s = "{$this->command} " . $this->title->getPrefixedDBkey();
			if ( $this->params !== '' ) {
				$s .= ', ' . $this->params;
			}
			return $s;
		} else {
			return "{$this->command} {$this->params}";
		}			
	}

	function getLastError() {
		return $this->error;
	}
}
