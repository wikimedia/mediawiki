<?php
/**
 * Remove old objects from the parser cache.
 * This only works when the parser cache is in an SQL database.
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

require __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to remove old objects from the parser cache.
 *
 * @ingroup Maintenance
 */
class PurgeParserCache extends Maintenance {
	public $lastProgress;

	private $usleep = 0;

	function __construct() {
		parent::__construct();
		$this->addDescription( "Remove old objects from the parser cache. " .
			"This only works when the parser cache is in an SQL database." );
		$this->addOption( 'expiredate', 'Delete objects expiring before this date.', false, true );
		$this->addOption(
			'age',
			'Delete objects created more than this many seconds ago, assuming ' .
				'$wgParserCacheExpireTime has remained consistent.',
			false,
			true );
		$this->addOption( 'msleep', 'Milliseconds to sleep between purge chunks', false, true );
	}

	function execute() {
		global $wgParserCacheExpireTime;

		$inputDate = $this->getOption( 'expiredate' );
		$inputAge = $this->getOption( 'age' );
		if ( $inputDate !== null ) {
			$date = wfTimestamp( TS_MW, strtotime( $inputDate ) );
		} elseif ( $inputAge !== null ) {
			$date = wfTimestamp( TS_MW, time() + $wgParserCacheExpireTime - intval( $inputAge ) );
		} else {
			$this->error( "Must specify either --expiredate or --age", 1 );
			return;
		}
		$this->usleep = 1e3 * $this->getOption( 'msleep', 0 );

		$english = Language::factory( 'en' );
		$this->output( "Deleting objects expiring before " .
			$english->timeanddate( $date ) . "\n" );

		$pc = wfGetParserCacheStorage();
		$success = $pc->deleteObjectsExpiringBefore( $date, [ $this, 'showProgressAndWait' ] );
		if ( !$success ) {
			$this->error( "\nCannot purge this kind of parser cache.", 1 );
		}
		$this->showProgressAndWait( 100 );
		$this->output( "\nDone\n" );
	}

	public function showProgressAndWait( $percent ) {
		usleep( $this->usleep ); // avoid lag; T150124

		$percentString = sprintf( "%.2f", $percent );
		if ( $percentString === $this->lastProgress ) {
			return;
		}
		$this->lastProgress = $percentString;

		$stars = floor( $percent / 2 );
		$this->output( '[' . str_repeat( '*', $stars ) . str_repeat( '.', 50 - $stars ) . '] ' .
			"$percentString%\r" );
	}
}

$maintClass = 'PurgeParserCache';
require_once RUN_MAINTENANCE_IF_MAIN;
