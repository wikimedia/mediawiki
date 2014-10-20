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

require_once __DIR__ . '/Maintenance.php';

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
		global $wgQueryCacheLimit, $wgDisableQueryPageUpdate;

		$dbw = wfGetDB( DB_MASTER );

		$this->doSpecialPageCacheUpdates( $dbw );

		foreach ( QueryPage::getPages() as $page ) {
			list( $class, $special ) = $page;
			$limit = isset( $page[2] ) ? $page[2] : null;

			# --list : just show the name of pages
			if ( $this->hasOption( 'list' ) ) {
				$this->output( "$special [QueryPage]\n" );
				continue;
			}

			if ( !$this->hasOption( 'override' )
				&& $wgDisableQueryPageUpdate && in_array( $special, $wgDisableQueryPageUpdate )
			) {
				$this->output( sprintf( "%-30s [QueryPage] disabled\n", $special ) );
				continue;
			}

			$specialObj = SpecialPageFactory::getPage( $special );
			if ( !$specialObj ) {
				$this->output( "No such special page: $special\n" );
				exit;
			}
			if ( $specialObj instanceof QueryPage ) {
				$queryPage = $specialObj;
			} else {
				if ( !class_exists( $class ) ) {
					$file = $specialObj->getFile();
					require_once $file;
				}
				$queryPage = new $class;
			}

			if ( !$this->hasOption( 'only' ) || $this->getOption( 'only' ) == $queryPage->getName() ) {
				$this->output( sprintf( '%-30s [QueryPage] ', $special ) );
				if ( $queryPage->isExpensive() ) {
					$t1 = microtime( true );
					# Do the query
					$num = $queryPage->recache( $limit === null ? $wgQueryCacheLimit : $limit );
					$t2 = microtime( true );
					if ( $num === false ) {
						$this->output( "FAILED: database error\n" );
					} else {
						$this->output( "got $num rows in " );

						$elapsed = $t2 - $t1;
						$hours = intval( $elapsed / 3600 );
						$minutes = intval( $elapsed % 3600 / 60 );
						$seconds = $elapsed - $hours * 3600 - $minutes * 60;
						if ( $hours ) {
							$this->output( $hours . 'h ' );
						}
						if ( $minutes ) {
							$this->output( $minutes . 'm ' );
						}
						$this->output( sprintf( "%.2fs\n", $seconds ) );
					}
					# Reopen any connections that have closed
					if ( !wfGetLB()->pingAll() ) {
						$this->output( "\n" );
						do {
							$this->error( "Connection failed, reconnecting in 10 seconds..." );
							sleep( 10 );
						} while ( !wfGetLB()->pingAll() );
						$this->output( "Reconnected\n\n" );
					}
					# Wait for the slave to catch up
					wfWaitForSlaves();
				} else {
					$this->output( "cheap, skipped\n" );
				}
				if ( $this->hasOption( 'only' ) ) {
					break;
				}
			}
		}
	}

	public function doSpecialPageCacheUpdates( $dbw ) {
		global $wgSpecialPageCacheUpdates;

		foreach ( $wgSpecialPageCacheUpdates as $special => $call ) {
			# --list : just show the name of pages
			if ( $this->hasOption( 'list' ) ) {
				$this->output( "$special [callback]\n" );
				continue;
			}

			if ( !$this->hasOption( 'only' ) || $this->getOption( 'only' ) == $special ) {
				if ( !is_callable( $call ) ) {
					$this->error( "Uncallable function $call!" );
					continue;
				}
				$this->output( sprintf( '%-30s [callback] ', $special ) );
				$t1 = microtime( true );
				call_user_func( $call, $dbw );
				$t2 = microtime( true );

				$this->output( "completed in " );
				$elapsed = $t2 - $t1;
				$hours = intval( $elapsed / 3600 );
				$minutes = intval( $elapsed % 3600 / 60 );
				$seconds = $elapsed - $hours * 3600 - $minutes * 60;
				if ( $hours ) {
					$this->output( $hours . 'h ' );
				}
				if ( $minutes ) {
					$this->output( $minutes . 'm ' );
				}
				$this->output( sprintf( "%.2fs\n", $seconds ) );
				# Wait for the slave to catch up
				wfWaitForSlaves();
			}
		}
	}
}

$maintClass = "UpdateSpecialPages";
require_once RUN_MAINTENANCE_IF_MAIN;
