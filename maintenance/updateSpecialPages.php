<?php
/**
 * Update for cached special pages.
 * Run this script periodically if you have miser mode enabled.
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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\SpecialPage\QueryPage;

/**
 * Maintenance script to update cached special pages.
 *
 * @ingroup Maintenance
 */
class UpdateSpecialPages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'list', 'List special page names' );
		$this->addOption( 'only', 'Only update "page"; case sensitive, ' .
			'check correct case by calling this script with --list. ' .
			'Ex: --only=BrokenRedirects', false, true );
		$this->addOption( 'override', 'Also update pages that have updates disabled' );
	}

	public function execute() {
		$dbw = $this->getPrimaryDB();
		$config = $this->getConfig();
		$specialPageFactory = $this->getServiceContainer()->getSpecialPageFactory();

		$this->doSpecialPageCacheUpdates( $dbw );

		$queryCacheLimit = (int)$config->get( MainConfigNames::QueryCacheLimit );
		$disabledQueryPages = QueryPage::getDisabledQueryPages( $config );
		foreach ( QueryPage::getPages() as $page ) {
			[ , $special ] = $page;
			$limit = $page[2] ?? $queryCacheLimit;

			# --list : just show the name of pages
			if ( $this->hasOption( 'list' ) ) {
				$this->output( "$special [QueryPage]\n" );
				continue;
			}

			if ( !$this->hasOption( 'override' )
				&& isset( $disabledQueryPages[$special] )
			) {
				$this->output( sprintf( "%-30s [QueryPage] disabled\n", $special ) );
				continue;
			}

			$specialObj = $specialPageFactory->getPage( $special );
			if ( !$specialObj ) {
				$this->output( "No such special page: $special\n" );
				return;
			}
			if ( $specialObj instanceof QueryPage ) {
				$queryPage = $specialObj;
			} else {
				$class = get_class( $specialObj );
				$this->fatalError( "$class is not an instance of QueryPage.\n" );
			}

			if ( !$this->hasOption( 'only' ) || $this->getOption( 'only' ) === $queryPage->getName() ) {
				$this->output( sprintf( '%-30s [QueryPage] ', $special ) );
				if ( $queryPage->isExpensive() ) {
					$t1 = microtime( true );
					# Do the query
					$num = $queryPage->recache( $limit );
					$t2 = microtime( true );
					if ( $num === false ) {
						$this->output( "FAILED: database error\n" );
					} else {
						$this->output( "got $num rows in " );
						$this->outputElapsedTime( $t2 - $t1 );
					}
					# Reopen any connections that have closed
					$this->reopenAndWaitForReplicas();
				} else {
					// Check if this page was expensive before and now cheap
					$cached = $queryPage->getCachedTimestamp();
					if ( $cached ) {
						$queryPage->deleteAllCachedData();
						$this->reopenAndWaitForReplicas();
						$this->output( "cheap, but deleted cached data\n" );
					} else {
						$this->output( "cheap, skipped\n" );
					}
				}
				if ( $this->hasOption( 'only' ) ) {
					break;
				}
			}
		}
	}

	/**
	 * Re-open any closed db connection, and wait for replicas
	 *
	 * Queries that take a really long time, might cause the
	 * mysql connection to "go away"
	 */
	private function reopenAndWaitForReplicas() {
		$lbFactory = $this->getServiceContainer()->getDBLoadBalancerFactory();
		$lb = $lbFactory->getMainLB();
		if ( !$lb->pingAll() ) {
			// We don't want the tests to sleep for 10 seconds, so mark this as ignored because there is no reason to
			// test it.
			// @codeCoverageIgnoreStart
			$this->output( "\n" );
			do {
				$this->error( "Connection failed, reconnecting in 10 seconds..." );
				sleep( 10 );
				$this->waitForReplication();
			} while ( !$lb->pingAll() );
			$this->output( "Reconnected\n\n" );
			// @codeCoverageIgnoreEnd
		}
		$this->waitForReplication();
	}

	public function doSpecialPageCacheUpdates( $dbw ) {
		foreach ( $this->getConfig()->get( MainConfigNames::SpecialPageCacheUpdates ) as $special => $call ) {
			# --list : just show the name of pages
			if ( $this->hasOption( 'list' ) ) {
				$this->output( "$special [callback]\n" );
				continue;
			}

			if ( !$this->hasOption( 'only' ) || $this->getOption( 'only' ) === $special ) {
				$this->output( sprintf( '%-30s [callback] ', $special ) );
				if ( !is_callable( $call ) ) {
					$this->error( "Uncallable function $call!" );
					continue;
				}
				$t1 = microtime( true );
				$call( $dbw );
				$t2 = microtime( true );

				$this->output( "completed in " );
				$this->outputElapsedTime( $t2 - $t1 );

				# Wait for the replica DB to catch up
				$this->reopenAndWaitForReplicas();
			}
		}
	}

	/**
	 * Outputs the time that was elapsed to update the cache update for
	 * a script.
	 *
	 * @param float $elapsed
	 * @return void
	 */
	private function outputElapsedTime( float $elapsed ) {
		$hours = intval( $elapsed / 3600 );
		$minutes = intval( (int)$elapsed % 3600 / 60 );
		$seconds = $elapsed - $hours * 3600 - $minutes * 60;
		if ( $hours ) {
			$this->output( $hours . 'h ' );
		}
		if ( $minutes ) {
			$this->output( $minutes . 'm ' );
		}
		$this->output( sprintf( "%.2fs\n", $seconds ) );
	}
}

// @codeCoverageIgnoreStart
$maintClass = UpdateSpecialPages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
