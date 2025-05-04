<?php
/**
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
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Remove expired objects from the parser cache database.
 *
 * By default, this does not need to be run. The default parser cache
 * backend is CACHE_DB (SqlBagOStuff), and by default that automatically
 * performs incremental purges in the background of write requests.
 *
 * @see {@link MediaWiki\MainConfigSchema::ParserCacheType}
 * @ingroup Maintenance
 */
class PurgeParserCache extends Maintenance {

	/** @var null|string */
	private $lastProgress;

	/** @var null|float */
	private $lastTimestamp;

	/** @var int */
	private $tmpCount = 0;
	/** @var float */
	private $usleep = 0;

	public function __construct() {
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
		$this->addOption( 'dry-run', 'Perform a dry run, to verify age and date calculation.' );
		$this->addOption( 'msleep', 'Milliseconds to sleep between purge chunks of $wgUpdateRowsPerQuery.',
			false,
			true );
		$this->addOption(
			'tag',
			'Purge a single server only. This feature is designed for use by large wiki farms where ' .
				'one has to purge multiple servers concurrently in order to keep up with new writes. ' .
				'This requires using the SqlBagOStuff "servers" option in $wgObjectCaches.',
			false,
			true );
	}

	public function execute() {
		$inputDate = $this->getOption( 'expiredate' );
		$inputAge = $this->getOption( 'age' );

		if ( $inputDate !== null ) {
			$timestamp = strtotime( $inputDate );
		} elseif ( $inputAge !== null ) {
			$expireTime = (int)$this->getConfig()->get( MainConfigNames::ParserCacheExpireTime );
			$timestamp = time() + $expireTime - intval( $inputAge );
		} else {
			$this->fatalError( "Must specify either --expiredate or --age" );
		}
		$this->usleep = 1e3 * $this->getOption( 'msleep', 0 );
		$this->lastTimestamp = microtime( true );

		$humanDate = ConvertibleTimestamp::convert( TS_RFC2822, $timestamp );
		if ( $this->hasOption( 'dry-run' ) ) {
			$this->fatalError( "\nDry run mode, would delete objects having an expiry before " . $humanDate . "\n" );
		}

		$this->output( "Deleting objects expiring before " . $humanDate . "\n" );

		$pc = $this->getServiceContainer()->getParserCache()->getCacheStorage();
		$success = $pc->deleteObjectsExpiringBefore(
			$timestamp,
			[ $this, 'showProgressAndWait' ],
			INF,
			// Note that "0" can be a valid server tag, and must not be discarded or changed to null.
			$this->getOption( 'tag', null )
		);
		if ( !$success ) {
			$this->fatalError( "\nCannot purge this kind of parser cache." );
		}
		$this->showProgressAndWait( 100 );
		$this->output( "\nDone\n" );
	}

	public function showProgressAndWait( int $percent ) {
		// Parser caches involve mostly-unthrottled writes of large blobs. This is sometimes prone
		// to replication lag. As such, while our purge queries are simple primary key deletes,
		// we want to avoid adding significant load to the replication stream, by being
		// proactively graceful with these sleeps between each batch.
		// The reason we don't explicitly wait for replication is that that would require the script
		// to be aware of cross-dc replicas, which we prefer not to, and waiting for replication
		// and confirmation latency might actually be *too* graceful and take so long that the
		// purge script would not be able to finish within 24 hours for large wiki farms.
		// (T150124).
		usleep( $this->usleep );
		$this->tmpCount++;

		$percentString = sprintf( "%.1f", $percent );
		if ( $percentString === $this->lastProgress ) {
			// Only print a line if we've progressed >= 0.1% since the last printed line.
			// This does not mean every 0.1% step is printed since we only run this callback
			// once after a deletion batch. How often and how many lines we print depends on the
			// batch size (SqlBagOStuff::deleteObjectsExpiringBefore, $wgUpdateRowsPerQuery),
			// and on how many table rows there are.
			return;
		}
		$now = microtime( true );
		$sec = sprintf( "%.1f", $now - $this->lastTimestamp );

		// Give a sense of how much time is spent in the delete operations vs the sleep time,
		// by recording the number of iterations we've completed since the last progress update.
		$this->output( "... {$percentString}% done (+{$this->tmpCount} iterations in {$sec}s)\n" );

		$this->lastProgress = $percentString;
		$this->tmpCount = 0;
		$this->lastTimestamp = $now;
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgeParserCache::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
