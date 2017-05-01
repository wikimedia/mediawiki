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
	/** @var string Binlog file */
	public $file;
	/** @var int Binglog file position */
	public $pos;
	/** @var string[] GTID list */
	public $gtids = [];
	/** @var float UNIX timestamp */
	public $asOfTime = 0.0;

	/**
	 * @param string $file Binlog file name
	 * @param integer $pos Binlog position
	 * @param string $gtid Comma separated GTID set [optional]
	 */
	function __construct( $file, $pos, $gtid = '' ) {
		$this->file = $file;
		$this->pos = $pos;
		$this->gtids = array_map( 'trim', explode( ',', $gtid ) );
		$this->asOfTime = microtime( true );
	}

	/**
	 * @return string <binlog file>/<position>, e.g db1034-bin.000976/843431247
	 */
	function __toString() {
		return "{$this->file}/{$this->pos}";
	}

	function asOfTime() {
		return $this->asOfTime;
	}

	function hasReached( DBMasterPos $pos ) {
		if ( !( $pos instanceof self ) ) {
			throw new InvalidArgumentException( "Position not an instance of " . __CLASS__ );
		}

		// Prefer GTID comparisons, which work with multi-tier replication
		$thisPosByDomain = $this->getGtidCoordinates();
		$thatPosByDomain = $pos->getGtidCoordinates();
		if ( $thisPosByDomain && $thatPosByDomain ) {
			$reached = true;
			// Check that this has positions GTE all of those in $pos for all domains in $pos
			foreach ( $thatPosByDomain as $domain => $thatPos ) {
				$thisPos = isset( $thisPosByDomain[$domain] ) ? $thisPosByDomain[$domain] : -1;
				$reached = $reached && ( $thatPos <= $thisPos );
			}

			return $reached;
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

	function channelsMatch( DBMasterPos $pos ) {
		if ( !( $pos instanceof self ) ) {
			throw new InvalidArgumentException( "Position not an instance of " . __CLASS__ );
		}

		// Prefer GTID comparisons, which work with multi-tier replication
		$thisPosDomains = array_keys( $this->getGtidCoordinates() );
		$thatPosDomains = array_keys( $pos->getGtidCoordinates() );
		if ( $thisPosDomains && $thatPosDomains ) {
			// Check that this has GTIDs for all domains in $pos
			return !array_diff( $thatPosDomains, $thisPosDomains );
		}

		// Fallback to the binlog file comparisons
		$thisBinPos = $this->getBinlogCoordinates();
		$thatBinPos = $pos->getBinlogCoordinates();

		return ( $thisBinPos && $thatBinPos && $thisBinPos['binlog'] === $thatBinPos['binlog'] );
	}

	/**
	 * @note: this returns false for multi-source replication GTID sets
	 * @see https://mariadb.com/kb/en/mariadb/gtid
	 * @see https://dev.mysql.com/doc/refman/5.6/en/replication-gtids-concepts.html
	 * @return array Map of (domain => integer position) or false
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
		$m = [];
		if ( preg_match( '!^(.+)\.(\d+)/(\d+)$!', (string)$this, $m ) ) {
			return [ 'binlog' => $m[1], 'pos' => [ (int)$m[2], (int)$m[3] ] ];
		}

		return false;
	}
}
