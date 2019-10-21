<?php
/**
 * Send purge requests for listed pages to CDN
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that sends purge requests for listed pages to CDN.
 *
 * @ingroup Maintenance
 */
class PurgeList extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Send purge requests for listed pages to CDN' );
		$this->addOption( 'purge', 'Whether to update page_touched.', false, false );
		$this->addOption( 'namespace', 'Namespace number', false, true );
		$this->addOption( 'all', 'Purge all pages', false, false );
		$this->addOption( 'delay', 'Number of seconds to delay between each purge', false, true );
		$this->addOption( 'verbose', 'Show more output', false, false, 'v' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		if ( $this->hasOption( 'all' ) ) {
			$this->purgeNamespace( false );
		} elseif ( $this->hasOption( 'namespace' ) ) {
			$this->purgeNamespace( intval( $this->getOption( 'namespace' ) ) );
		} else {
			$this->doPurge();
		}
		$this->output( "Done!\n" );
	}

	/**
	 * Purge URL coming from stdin
	 */
	private function doPurge() {
		$stdin = $this->getStdin();
		$urls = [];

		while ( !feof( $stdin ) ) {
			$page = trim( fgets( $stdin ) );
			if ( preg_match( '%^https?://%', $page ) ) {
				$urls[] = $page;
			} elseif ( $page !== '' ) {
				$title = Title::newFromText( $page );
				if ( $title ) {
					$newUrls = $title->getCdnUrls();

					foreach ( $newUrls as $url ) {
						$this->output( "$url\n" );
					}

					$urls = array_merge( $urls, $newUrls );

					if ( $this->getOption( 'purge' ) ) {
						$title->invalidateCache();
					}
				} else {
					$this->output( "(Invalid title '$page')\n" );
				}
			}
		}
		$this->output( "Purging " . count( $urls ) . " urls\n" );
		$this->sendPurgeRequest( $urls );
	}

	/**
	 * Purge a namespace or all pages
	 *
	 * @param int|bool $namespace
	 */
	private function purgeNamespace( $namespace = false ) {
		$dbr = $this->getDB( DB_REPLICA );
		$startId = 0;
		if ( $namespace === false ) {
			$conds = [];
		} else {
			$conds = [ 'page_namespace' => $namespace ];
		}
		while ( true ) {
			$res = $dbr->select( 'page',
				[ 'page_id', 'page_namespace', 'page_title' ],
				$conds + [ 'page_id > ' . $dbr->addQuotes( $startId ) ],
				__METHOD__,
				[
					'LIMIT' => $this->getBatchSize(),
					'ORDER BY' => 'page_id'

				]
			);
			if ( !$res->numRows() ) {
				break;
			}
			$urls = [];
			foreach ( $res as $row ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$urls = array_merge( $urls, $title->getCdnUrls() );
				$startId = $row->page_id;
			}
			$this->sendPurgeRequest( $urls );
		}
	}

	/**
	 * Helper to purge an array of $urls
	 * @param array $urls List of URLS to purge from CDNs
	 */
	private function sendPurgeRequest( $urls ) {
		if ( $this->hasOption( 'delay' ) ) {
			$delay = floatval( $this->getOption( 'delay' ) );
			foreach ( $urls as $url ) {
				if ( $this->hasOption( 'verbose' ) ) {
					$this->output( $url . "\n" );
				}
				$u = new CdnCacheUpdate( [ $url ] );
				$u->doUpdate();
				usleep( $delay * 1e6 );
			}
		} else {
			if ( $this->hasOption( 'verbose' ) ) {
				$this->output( implode( "\n", $urls ) . "\n" );
			}
			$u = new CdnCacheUpdate( $urls );
			$u->doUpdate();
		}
	}
}

$maintClass = PurgeList::class;
require_once RUN_MAINTENANCE_IF_MAIN;
