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

use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
	/** @var int */
	private $delay;

	public function __construct() {
		parent::__construct();
		$this->addDescription( "Send purge requests for listed pages to CDN.\n"
			. "By default this expects a list of URLs or page names from STDIN. "
			. "To query the database for input, use --namespace or --all-namespaces instead."
		);
		$this->addOption( 'namespace', 'Purge pages with this namespace number', false, true );
		$this->addOption( 'all-namespaces', 'Purge pages in all namespaces', false, false );
		$this->addOption( 'db-touch',
			"Update the page.page_touched database field.\n"
				. "This is only considered when purging by title, not when purging by namespace or URL.",
			false,
			false
		);
		$this->addOption( 'delay', 'Number of seconds to delay between each purge', false, true );
		$this->addOption( 'verbose', 'Show more output', false, false, 'v' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$this->namespaceId = $this->getOption( 'namespace' );
		$this->allNamespaces = $this->hasOption( 'all-namespaces' );
		$this->doDbTouch = $this->hasOption( 'db-touch' );
		$this->delay = intval( $this->getOption( 'delay', '0' ) );

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
		$htmlCacheUpdater = $this->getServiceContainer()->getHtmlCacheUpdater();

		while ( !feof( $stdin ) ) {
			$page = trim( fgets( $stdin ) );
			if ( preg_match( '%^https?://%', $page ) ) {
				$urls[] = $page;
			} elseif ( $page !== '' ) {
				$title = Title::newFromText( $page );
				if ( $title ) {
					$newUrls = $htmlCacheUpdater->getUrls( $title );

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
		if ( $this->doDbTouch ) {
			// NOTE: If support for this is added in the future,
			// it MUST NOT be allowed when $wgMiserMode is enabled.
			// Change this to a check and error about instead! (T263957)
			$this->fatalError( 'The --db-touch option is not supported when purging by namespace.' );
		}

		$dbr = $this->getReplicaDB();
		$htmlCacheUpdater = $this->getServiceContainer()->getHtmlCacheUpdater();
		$startId = 0;
		if ( $namespace === false ) {
			$conds = [];
		} else {
			$conds = [ 'page_namespace' => $namespace ];
		}
		while ( true ) {
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'page_id', 'page_namespace', 'page_title' ] )
				->from( 'page' )
				->where( $conds )
				->andWhere( $dbr->expr( 'page_id', '>', $startId ) )
				->orderBy( 'page_id' )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )->fetchResultSet();
			if ( !$res->numRows() ) {
				break;
			}
			$urls = [];
			foreach ( $res as $row ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$urls = array_merge( $urls, $htmlCacheUpdater->getUrls( $title ) );
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
		$hcu = $this->getServiceContainer()->getHtmlCacheUpdater();
		if ( $this->delay > 0 ) {
			foreach ( $urls as $url ) {
				if ( $this->hasOption( 'verbose' ) ) {
					$this->output( $url . "\n" );
				}
				$hcu->purgeUrls( $url, $hcu::PURGE_NAIVE );
				sleep( $this->delay );
			}
		} else {
			if ( $this->hasOption( 'verbose' ) ) {
				$this->output( implode( "\n", $urls ) . "\n" );
			}
			$hcu->purgeUrls( $urls, $hcu::PURGE_NAIVE );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgeList::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
