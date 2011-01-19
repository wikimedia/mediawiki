<?php
/**
 * Run this script periodically if you have miser mode enabled, to refresh the
 * caches
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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class UpdateSpecialPages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'list', 'List special page names' );
		$this->addOption( 'only', 'Only update "page". Ex: --only=BrokenRedirects', false, true );
		$this->addOption( 'override', 'Also update pages that have updates disabled' );
	}

	public function execute() {
		global $IP, $wgOut, $wgSpecialPageCacheUpdates, $wgQueryPages, $wgQueryCacheLimit, $wgDisableQueryPageUpdate;
		$wgOut->disable();
		$dbw = wfGetDB( DB_MASTER );

		foreach ( $wgSpecialPageCacheUpdates as $special => $call ) {
			if ( !is_callable( $call ) ) {
				$this->error( "Uncallable function $call!" );
				continue;
			}
			$t1 = explode( ' ', microtime() );
			call_user_func( $call, $dbw );
			$t2 = explode( ' ', microtime() );
			$this->output( sprintf( '%-30s ', $special ) );
			$elapsed = ( $t2[0] - $t1[0] ) + ( $t2[1] - $t1[1] );
			$hours = intval( $elapsed / 3600 );
			$minutes = intval( $elapsed % 3600 / 60 );
			$seconds = $elapsed - $hours * 3600 - $minutes * 60;
			if ( $hours ) {
				$this->output( $hours . 'h ' );
			}
			if ( $minutes ) {
				$this->output( $minutes . 'm ' );
			}
			$this->output( sprintf( "completed in %.2fs\n", $seconds ) );
			# Wait for the slave to catch up
			wfWaitForSlaves( 5 );
		}

		// This is needed to initialise $wgQueryPages
		require_once( "$IP/includes/QueryPage.php" );

		foreach ( $wgQueryPages as $page ) {
			@list( $class, $special, $limit ) = $page;

			# --list : just show the name of pages
			if ( $this->hasOption( 'list' ) ) {
				$this->output( "$special\n" );
				continue;
			}

			if ( !$this->hasOption( 'override' ) && $wgDisableQueryPageUpdate && in_array( $special, $wgDisableQueryPageUpdate ) ) {
				$this->output( sprintf( "%-30s disabled\n", $special ) );
				continue;
			}

			$specialObj = SpecialPage::getPage( $special );
			if ( !$specialObj ) {
				$this->output( "No such special page: $special\n" );
				exit;
			}
			if ( !class_exists( $class ) ) {
				$file = $specialObj->getFile();
				require_once( $file );
			}
			$queryPage = new $class;

			if ( !$this->hasOption( 'only' ) || $this->getOption( 'only' ) == $queryPage->getName() ) {
				$this->output( sprintf( '%-30s ',  $special ) );
				if ( $queryPage->isExpensive() ) {
					$t1 = explode( ' ', microtime() );
					# Do the query
					$num = $queryPage->recache( $limit === null ? $wgQueryCacheLimit : $limit );
					$t2 = explode( ' ', microtime() );
					if ( $num === false ) {
						$this->output( "FAILED: database error\n" );
					} else {
						$this->output( "got $num rows in " );

						$elapsed = ( $t2[0] - $t1[0] ) + ( $t2[1] - $t1[1] );
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
					if ( !wfGetLB()->pingAll() )  {
						$this->output( "\n" );
						do {
							$this->error( "Connection failed, reconnecting in 10 seconds..." );
							sleep( 10 );
						} while ( !wfGetLB()->pingAll() );
						$this->output( "Reconnected\n\n" );
					} else {
						# Commit the results
						$dbw->commit();
					}
					# Wait for the slave to catch up
					wfWaitForSlaves( 5 );
				} else {
					$this->output( "cheap, skipped\n" );
				}
			}
		}
	}
}

$maintClass = "UpdateSpecialPages";
require_once( RUN_MAINTENANCE_IF_MAIN );
