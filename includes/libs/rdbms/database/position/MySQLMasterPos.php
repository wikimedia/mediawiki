<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * DBMasterPos class for MySQL/MariaDB
 *
 * Note that master positions and sync logic here make some assumptions:
 *  - Binlog-based usage assumes single-source replication and non-hierarchical replication.
 *  - GTID-based usage allows getting/syncing with multi-source replication. It is assumed
 *    that GTID sets are complete (e.g. include all domains on the server).
 */
class MySQLMasterPos implements DBMasterPos {
	/** @var string|null Binlog file base name */
	public $binlog;
	/** @var int[]|null Binglog file position tuple */
	public $pos;
	/** @var string[] GTID list */
	public $gtids = [];
	/** @var float UNIX timestamp */
	public $asOfTime = 0.0;

	/**
	 * @param string $position One of (comma separated GTID list, <binlog file>/<integer>)
	 * @param float $asOfTime UNIX timestamp
	 */
	public function __construct( $position, $asOfTime ) {
		$m = [];
		if ( preg_match( '!^(.+)\.(\d+)/(\d+)$!', $position, $m ) ) {
			$this->binlog = $m[1]; // ideally something like host name
			$this->pos = [ (int)$m[2], (int)$m[3] ];
		} else {
			$this->gtids = array_map( 'trim', explode( ',', $position ) );
			if ( !$this->gtids ) {
				throw new InvalidArgumentException( "GTID set should not be empty." );
			}
		}

		$this->asOfTime = $asOfTime;
	}

	public function asOfTime() {
		return $this->asOfTime;
	}

	public function hasReached( DBMasterPos $pos ) {
		if ( !( $pos instanceof self ) ) {
			throw new InvalidArgumentException( "Position not an instance of " . __CLASS__ );
		}

		// Prefer GTID comparisons, which work with multi-tier replication
		$thisPosByDomain = $this->getGtidCoordinates();
		$thatPosByDomain = $pos->getGtidCoordinates();
		if ( $thisPosByDomain && $thatPosByDomain ) {
			$comparisons = [];
			// Check that this has positions reaching those in $pos for all domains in common
			foreach ( $thatPosByDomain as $domain => $thatPos ) {
				if ( isset( $thisPosByDomain[$domain] ) ) {
					$comparisons[] = ( $thatPos <= $thisPosByDomain[$domain] );
				}
			}
			// Check that $this has a GTID for at least one domain also in $pos; due to MariaDB
			// quirks, prior master switch-overs may result in inactive garbage GTIDs that cannot
			// be cleaned up. Assume that the domains in both this and $pos cover the relevant
			// active channels.
			return ( $comparisons && !in_array( false, $comparisons, true ) );
		}

		// Fallback to the binlog file comparisons
		$thisBinPos = $this->getBinlogCoordinates();
		$thatBinPos = $pos->getBinlogCoordinates();
		if ( $thisBinPos && $thatBinPos && $thisBinPos['binlog'] === $thatBinPos['binlog'] ) {
			return ( $thisBinPos['pos'] >= $thatBinPos['pos'] );
		}

		// Comparing totally different binlogs does not make sense
		return false;
	}

	public function channelsMatch( DBMasterPos $pos ) {
		if ( !( $pos instanceof self ) ) {
			throw new InvalidArgumentException( "Position not an instance of " . __CLASS__ );
		}

		// Prefer GTID comparisons, which work with multi-tier replication
		$thisPosDomains = array_keys( $this->getGtidCoordinates() );
		$thatPosDomains = array_keys( $pos->getGtidCoordinates() );
		if ( $thisPosDomains && $thatPosDomains ) {
			// Check that $this has a GTID for at least one domain also in $pos; due to MariaDB
			// quirks, prior master switch-overs may result in inactive garbage GTIDs that cannot
			// easily be cleaned up. Assume that the domains in both this and $pos cover the
			// relevant active channels.
			return array_intersect( $thatPosDomains, $thisPosDomains ) ? true : false;
		}

		// Fallback to the binlog file comparisons
		$thisBinPos = $this->getBinlogCoordinates();
		$thatBinPos = $pos->getBinlogCoordinates();

		return ( $thisBinPos && $thatBinPos && $thisBinPos['binlog'] === $thatBinPos['binlog'] );
	}

	/**
	 * @return string|null
	 */
	public function getLogFile() {
		return $this->gtids ? null : "{$this->binlog}.{$this->pos[0]}";
	}

	/**
	 * @return string GTID set or <binlog file>/<position> (e.g db1034-bin.000976/843431247)
	 */
	public function __toString() {
		return $this->gtids
			? implode( ',', $this->gtids )
			: $this->getLogFile() . "/{$this->pos[1]}";
	}

	/**
	 * @note: this returns false for multi-source replication GTID sets
	 * @see https://mariadb.com/kb/en/mariadb/gtid
	 * @see https://dev.mysql.com/doc/refman/5.6/en/replication-gtids-concepts.html
	 * @return array Map of (domain => integer position); possibly empty
	 */
	protected function getGtidCoordinates() {
		$gtidInfos = [];
		foreach ( $this->gtids as $gtid ) {
			$m = [];
			// MariaDB style: <domain>-<server id>-<sequence number>
			if ( preg_match( '!^(\d+)-\d+-(\d+)$!', $gtid, $m ) ) {
				$gtidInfos[(int)$m[1]] = (int)$m[2];
				// MySQL style: <UUID domain>:<sequence number>
			} elseif ( preg_match( '!^(\w{8}-\w{4}-\w{4}-\w{4}-\w{12}):(\d+)$!', $gtid, $m ) ) {
				$gtidInfos[$m[1]] = (int)$m[2];
			} else {
				$gtidInfos = [];
				break; // unrecognized GTID
			}

		}

		return $gtidInfos;
	}

	/**
	 * @see https://dev.mysql.com/doc/refman/5.7/en/show-master-status.html
	 * @see https://dev.mysql.com/doc/refman/5.7/en/show-slave-status.html
	 * @return array|bool (binlog, (integer file number, integer position)) or false
	 */
	protected function getBinlogCoordinates() {
		return ( $this->binlog !== null && $this->pos !== null )
			? [ 'binlog' => $this->binlog, 'pos' => $this->pos ]
			: false;
	}
}
