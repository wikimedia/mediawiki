<?php
/**
 * Run this script periodically if you have miser mode enabled, to refresh the
 * caches
 *
 * @file
 * @ingroup Maintenance
 */
 
require_once( "Maintenance.php" );

class UpdateSpecialPages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addParam( 'list', 'List special page names' );
		$this->addParam( 'only', 'Only update "page". Ex: --only=BrokenRedirects', false, true );
		$this->addParam( 'override', 'Also update pages that have updates disabled' );
	}

	public function execute() {
		global $wgOut;
		$wgOut->disable();
		$dbw = wfGetDB( DB_MASTER );

		foreach( $wgSpecialPageCacheUpdates as $special => $call ) {
			if( !is_callable($call) ) {
				$this->error( "Uncallable function $call!\n" );
				continue;
			}
			$t1 = explode( ' ', microtime() );
			call_user_func( $call, $dbw );
			$t2 = explode( ' ', microtime() );
			$this->output( sprintf( '%-30s ', $special ) );
			$elapsed = ($t2[0] - $t1[0]) + ($t2[1] - $t1[1]);
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
	
		foreach( $wgQueryPages as $page ) {
			@list( $class, $special, $limit ) = $page;
	
			# --list : just show the name of pages
			if( $this->hasOption('list') ) {
				$this->output( "$special\n" );
				continue;
			}
	
			if ( $this->hasOption('override') && $wgDisableQueryPageUpdate && in_array( $special, $wgDisableQueryPageUpdate ) ) {
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
	
			if( !$this->hasOption('only') || $this->getOption('only') == $queryPage->getName() ) {
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
	
						$elapsed = ($t2[0] - $t1[0]) + ($t2[1] - $t1[1]);
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
				if ( !wfGetLB()->pingAll())  {
					$this->output( "\n" );
					do {
						$this->error( "Connection failed, reconnecting in 10 seconds...\n" );
						sleep(10);
					} while ( !wfGetLB()->pingAll() );
					$this->output( "Reconnected\n\n" );
				} else {
					# Commit the results
					$dbw->immediateCommit();
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

