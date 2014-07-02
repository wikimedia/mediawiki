<?php
/**
 * Script to populate a bloom filter with a BloomFilter* class
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
 * Script to populate a bloom filter with a BloomFilter* class
 *
 * @ingroup Maintenance
 */
class PopulateBloomFilter extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'cache', 'Bloom cache store name', true, true );
		$this->addOption( 'filter', 'Bloom filter name', true, true );
		$this->addOption( 'domain', 'Bloom filter domain', true, true );
		$this->addOption( 'delay', 'Sleep delay between batches (us)', false, true );
		$this->mDescription = "Populate the specified bloom filter";
	}

	public function execute() {
		$type = $this->getOption( 'filter' );
		$domain = $this->getOption( 'domain' );
		$bcache = BloomCache::get( $this->getOption( 'cache' ) );
		$delay = $this->getOption( 'delay', 1e5 );

		if ( !method_exists( "BloomFilter{$type}", 'merge' ) ) {
			$this->error( "No \"BloomFilter{$type}::merge\" method found.", 1 );
		}

		$virtualKey = "$domain:$type";
		$status = $bcache->getStatus( $virtualKey );
		if ( $status == false ) {
			$this->error( "Could not query virtual bloom filter '$virtualKey'.", 1 );
		}

		$startTime = microtime( true );
		$this->output( "Current timestamp is '$startTime'.\n" );
		$this->output( "Current filter timestamp is '{$status['asOfTime']}'.\n" );

		do {
			$status = call_user_func_array(
				array( "BloomFilter{$type}", 'merge' ),
				array( $bcache, $domain, $virtualKey, $status )
			);
			if ( $status == false ) {
				$this->error( "Could not query virtual bloom filter '$virtualKey'.", 1 );
			}
			$this->output( "Filter updated to timestamp '{$status['asOfTime']}'.\n" );
			usleep( $delay );
		} while ( $status['asOfTime'] && $status['asOfTime'] < $startTime );

		$this->output( "Done, filter $type of domain $domain reached time '$startTime'.\n" );
	}
}

$maintClass = "PopulateBloomFilter";
require_once RUN_MAINTENANCE_IF_MAIN;
