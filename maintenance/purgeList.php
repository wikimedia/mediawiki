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

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that sends purge requests for listed pages to CDN.
 *
 * @ingroup Maintenance
 */
class PurgeList extends Maintenance {
	/** @var string|null */
	private $namespaceId;
	/** @var bool */
	private $allNamespaces;
	/** @var bool */
	private $doDbTouch;
	/** @var float */
	private $delay;

	public function __construct() {
		parent::__construct();
		$this->addDescription( "Send purge requests for listed pages to CDN.\n"
			. "By default this expects a list of URLs or page names from STDIN. "
			. "To query the database for input, use --namespace or --all-namespaces instead."
		);
		$this->addOption( 'namespace', 'Purge pages with this namespace number', false, true );
		$this->addOption( 'all-namespaces', 'Purge pages in all namespaces', false, false );
		$this->addOption( 'db-touch', 'Update the page.page_touched database field', false, false );
		$this->addOption( 'delay', 'Number of seconds to delay between each purge', false, true );
		$this->addOption( 'verbose', 'Show more output', false, false, 'v' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$this->namespaceId = $this->getOption( 'namespace' );
		$this->allNamespaces = $this->hasOption( 'all-namespaces' );
		$this->doDbTouch = $this->hasOption( 'db-touch' );
		$this->delay = floatval( $this->getOption( 'delay', '0' ) );

		$conf = $this->getConfig();
		if ( ( $this->namespaceId !== null || $this->allNamespaces )
			&& $this->doDbTouch
			&& $conf->get( 'MiserMode' )
		) {
			$this->fatalError( 'Prevented mass db-invalidation (MiserMode is enabled).' );
		}

		if ( $this->allNamespaces ) {
			$this->purgeNamespace( false );
		} elseif ( $this->namespaceId !== null ) {
			$this->purgeNamespace( intval( $this->namespaceId ) );
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

					if ( $this->doDbTouch ) {
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
		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		if ( $this->delay > 0 ) {
			foreach ( $urls as $url ) {
				if ( $this->hasOption( 'verbose' ) ) {
					$this->output( $url . "\n" );
				}
				$hcu->purgeUrls( $url, $hcu::PURGE_NAIVE );
				usleep( $this->delay * 1e6 );
			}
		} else {
			if ( $this->hasOption( 'verbose' ) ) {
				$this->output( implode( "\n", $urls ) . "\n" );
			}
			$hcu->purgeUrls( $urls, $hcu::PURGE_NAIVE );
		}
	}
}

$maintClass = PurgeList::class;
require_once RUN_MAINTENANCE_IF_MAIN;
