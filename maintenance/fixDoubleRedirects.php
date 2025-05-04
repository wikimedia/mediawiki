<?php
/**
 * Fix double redirects.
 *
 * Copyright © 2011 Ilmari Karonen <nospam@vyznev.net>
 * https://www.mediawiki.org/
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

use MediaWiki\JobQueue\Jobs\DoubleRedirectJob;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that fixes double redirects.
 *
 * @ingroup Maintenance
 */
class FixDoubleRedirects extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to fix double redirects' );
		$this->addOption( 'async', 'Don\'t fix anything directly, just queue the jobs' );
		$this->addOption( 'title', 'Fix only redirects pointing to this page', false, true );
		$this->addOption( 'dry-run', 'Perform a dry run, fix nothing' );
	}

	public function execute() {
		$async = $this->hasOption( 'async' );
		$dryrun = $this->hasOption( 'dry-run' );

		if ( $this->hasOption( 'title' ) ) {
			$title = Title::newFromText( $this->getOption( 'title' ) );
			if ( !$title || !$title->isRedirect() ) {
				$this->fatalError( $title->getPrefixedText() . " is not a redirect!\n" );
			}
		} else {
			$title = null;
		}

		$dbr = $this->getReplicaDB();

		// See also SpecialDoubleRedirects
		// TODO: support batch querying
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( [
				'pa_namespace' => 'pa.page_namespace',
				'pa_title' => 'pa.page_title',
				'pb_namespace' => 'pb.page_namespace',
				'pb_title' => 'pb.page_title',
			] )
			->from( 'redirect' )
			->join( 'page', 'pa', 'rd_from = pa.page_id' )
			->join( 'page', 'pb', [ 'rd_namespace = pb.page_namespace', 'rd_title = pb.page_title' ] )
			// T42352
			->where( [ 'rd_interwiki' => '', 'pb.page_is_redirect' => 1 ] );

		if ( $title != null ) {
			$queryBuilder->andWhere( [
				'pb.page_namespace' => $title->getNamespace(),
				'pb.page_title' => $title->getDBkey()
			] );
		}
		$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		if ( !$res->numRows() ) {
			$this->output( "No double redirects found.\n" );

			return;
		}

		$jobs = [];
		$processedTitles = "\n";
		$n = 0;
		$services = $this->getServiceContainer();
		foreach ( $res as $row ) {
			$titleA = Title::makeTitle( $row->pa_namespace, $row->pa_title );
			$titleB = Title::makeTitle( $row->pb_namespace, $row->pb_title );
			if ( !$titleA->canExist() || !$titleB->canExist() ) {
				$this->error( "Cannot fix redirect from" .
					( $titleA->canExist() ? "" : " invalid" ) . " title " . $titleA->getPrefixedText()
					. " to new" .
					( $titleB->canExist() ? "" : " invalid" ) . " target " . $titleB->getPrefixedText()
					. "\n"
				);
				continue;
			}

			$processedTitles .= "* [[$titleA]]\n";

			$job = new DoubleRedirectJob(
				$titleA,
				[
					'reason' => 'maintenance',
					'redirTitle' => $titleB->getPrefixedDBkey()
				],
				$services->getRevisionLookup(),
				$services->getMagicWordFactory(),
				$services->getWikiPageFactory()
			);

			if ( !$async ) {
				$success = ( $dryrun ? true : $job->run() );
				if ( !$success ) {
					$this->error( "Error fixing " . $titleA->getPrefixedText()
						. ": " . $job->getLastError() . "\n" );
				}
			} else {
				$jobs[] = $job;
				if ( count( $jobs ) > DoubleRedirectJob::MAX_DR_JOBS_COUNTER ) {
					$this->queueJobs( $jobs, $dryrun );
					$jobs = [];
				}
			}

			if ( ++$n % 100 == 0 ) {
				$this->output( "$n...\n" );
			}
		}

		if ( count( $jobs ) ) {
			$this->queueJobs( $jobs, $dryrun );
		}
		$this->output( "$n double redirects processed" . $processedTitles . "\n" );
	}

	protected function queueJobs( array $jobs, bool $dryrun = false ) {
		$this->output( "Queuing batch of " . count( $jobs ) . " double redirects.\n" );
		$this->getServiceContainer()->getJobQueueGroup()->push( $dryrun ? [] : $jobs );
	}
}

// @codeCoverageIgnoreStart
$maintClass = FixDoubleRedirects::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
