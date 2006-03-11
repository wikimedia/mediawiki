<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "This file is part of MediaWiki, it is not a valid entry point\n" );
}

class Job {
	var $command,
		$title,
		$params,
		$id,
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

		$dbr =& wfGetDB( DB_SLAVE );

		// Get a job from the slave
		$row = $dbr->selectRow( 'job', '*', '', $fname,
			array( 'ORDER BY' => 'job_id', 'LIMIT' => 1 )
		);

		if ( $row === false ) {
			wfProfileOut( $fname );
			return false;
		}

		// Try to delete it from the master
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->delete( 'job', array( 'job_id' => $row->job_id ), $fname );
		$affected = $dbw->affectedRows();
		$dbw->immediateCommit();

		if ( !$affected ) {
			// Failed, someone else beat us to it
			// Try getting a random row
			$row = $dbw->selectRow( 'job', array( 'MIN(job_id) as minjob',
				'MAX(job_id) as maxjob' ), '', $fname );
			if ( $row === false || is_null( $row->minjob ) || is_null( $row->maxjob ) ) {
				// No jobs to get
				wfProfileOut( $fname );
				return false;
			}
			// Get the random row
			$row = $dbw->selectRow( 'job', '*',
				array( 'job_id' => mt_rand( $row->minjob, $row->maxjob ) ),	$fname );
			if ( $row === false ) {
				// Random job gone before we got the chance to select it
				// Give up
				wfProfileOut( $fname );
				return false;
			}
			// Delete the random row
			$dbw->delete( 'job', array( 'job_id' => $row->job_id ), $fname );
			$affected = $dbw->affectedRows();
			$dbw->immediateCommit();
			
			if ( !$affected ) {
				// Random job gone before we exclusively deleted it
				// Give up
				wfProfileOut( $fname );
				return false;
			}
		}
		
		// If execution got to here, there's a row in $row that has been deleted from the database
		// by this thread. Hence the concurrent pop was successful.
		$namespace = $row->job_namespace;
		$dbkey = $row->job_title;
		$title = Title::makeTitleSafe( $namespace, $dbkey );
		$job = new Job( $row->job_cmd, $title, $row->job_params, $row->job_id );
		wfProfileOut( $fname );
		return $job;
	}

	/*-------------------------------------------------------------------------
	 * Non-static functions
	 *------------------------------------------------------------------------*/

	function Job( $command, $title, $params = '', $id = 0 ) {
		$this->command = $command;
		$this->title = $title;
		$this->params = $params;
		$this->id = $id;

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
			$res = $dbw->select( 'job', array( '1' ), $fields, $fname );
			if ( $dbw->numRows( $res ) ) {
				return;
			}
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
		$fname = 'Job::refreshLinks';
		wfProfileIn( $fname );
		
		$dbw =& wfGetDB( DB_MASTER );

		$linkCache =& LinkCache::singleton();
		$linkCache->clear();
		
		if ( is_null( $this->title ) ) {
			$this->error = "refreshLinks: Invalid title";
			wfProfileOut( $fname );
			return false;
		}

		$revision = Revision::newFromTitle( $this->title );
		if ( !$revision ) {
			$this->error = 'refreshLinks: Article not found "' . $this->title->getPrefixedDBkey() . '"';
			wfProfileOut( $fname );
			return false;
		}

		wfProfileIn( "$fname-parse" );
		$options = new ParserOptions;
		$parserOutput = $wgParser->parse( $revision->getText(), $this->title, $options, true, true, $revision->getId() );
		wfProfileOut( "$fname-parse" );
		wfProfileIn( "$fname-update" );
		$update = new LinksUpdate( $this->title, $parserOutput, false );
		$update->doUpdate();
		wfProfileOut( "$fname-update" );
		wfProfileOut( $fname );
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
?>
