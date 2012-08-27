<?php
/**
 * Fix double redirects.
 *
 * Copyright © 2011 Ilmari Karonen <nospam@vyznev.net>
 * http://www.mediawiki.org/
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
 * @author Ilmari Karonen <nospam@vyznev.net>
 * @ingroup Maintenance
 */

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script that fixes double redirects.
 *
 * @ingroup Maintenance
 */
class FixDoubleRedirects extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Script to fix double redirects";
		$this->addOption( 'async', 'Don\'t fix anything directly, just queue the jobs' );
		$this->addOption( 'title', 'Fix only redirects pointing to this page', false, true );
		$this->addOption( 'dry-run', 'Perform a dry run, fix nothing' );
	}

	public function execute() {
		$async = $this->getOption( 'async', false );
		$dryrun = $this->getOption( 'dry-run', false );
		$title = $this->getOption( 'title' );

		if ( isset( $title ) ) {
			$title = Title::newFromText( $title );
			if ( !$title || !$title->isRedirect() ) {
				$this->error( $title->getPrefixedText() . " is not a redirect!\n", true );
			}
		}

		$dbr = wfGetDB( DB_SLAVE );

		$tables = array( 'redirect', 'pa' => 'page', 'pb' => 'page' );
		$fields = array(
			'pa.page_namespace AS pa_namespace',
			'pa.page_title AS pa_title',
			'pb.page_namespace AS pb_namespace',
			'pb.page_title AS pb_title',
		);
		$conds = array(
			'rd_from = pa.page_id',
			'rd_namespace = pb.page_namespace',
			'rd_title = pb.page_title',
			'pb.page_is_redirect' => 1,
		);

		if ( isset( $title ) ) {
			$conds['pb.page_namespace'] = $title->getNamespace();
			$conds['pb.page_title'] = $title->getDBkey();
		}
		// TODO: support batch querying

		$res = $dbr->select( $tables, $fields, $conds, __METHOD__ );

		if ( !$res->numRows() ) {
			$this->output( "No double redirects found.\n" );
			return;
		}

		$jobs = array();
		$n = 0;
		foreach ( $res as $row ) {
			$titleA = Title::makeTitle( $row->pa_namespace, $row->pa_title );
			$titleB = Title::makeTitle( $row->pb_namespace, $row->pb_title );

			$job = new DoubleRedirectJob( $titleA, array( 'reason' => 'maintenance', 'redirTitle' => $titleB->getPrefixedDBkey() ) );

			if ( !$async ) {
				$success = ( $dryrun ? true : $job->run() );
				if ( !$success ) {
					$this->error( "Error fixing " . $titleA->getPrefixedText() . ": " . $job->getLastError() . "\n" );
				}
			} else {
				$jobs[] = $job;
				// @todo FIXME: Hardcoded constant 10000 copied from DoubleRedirectJob class
				if ( count( $jobs ) > 10000 ) {
					$this->queueJobs( $jobs, $dryrun );
					$jobs = array();
				}
			}

			if ( ++$n % 100 == 0 ) {
				$this->output( "$n...\n" );
			}
		}

		if ( count( $jobs ) ) {
			$this->queueJobs( $jobs, $dryrun );
		}
		$this->output( "$n double redirects processed.\n" );
	}

	protected function queueJobs( $jobs, $dryrun = false ) {
		$this->output( "Queuing batch of " . count( $jobs ) . " double redirects.\n" );
		Job::batchInsert( $dryrun ? array() : $jobs );
	}
}

$maintClass = "FixDoubleRedirects";
require_once( RUN_MAINTENANCE_IF_MAIN );
