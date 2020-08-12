<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * DBMasterPos class for MySQL/MariaDB
 *
 * Note that master positions and sync logic here make some assumptions:
 *  - Binlog-based usage assumes single-source replication and non-hierarchical replication.
 *  - GTID-based usage allows getting/syncing with multi-source replication. It is assumed
 *    that GTID sets are complete (e.g. include all domains on the server).
 *
 * @see https://mariadb.com/kb/en/library/gtid/
 * @see https://dev.mysql.com/doc/refman/5.6/en/replication-gtids-concepts.html
 */
class MySQLMasterPos implements DBMasterPos {
	/** @var string One of (BINARY_LOG, GTID_MYSQL, GTID_MARIA) */
	private $style;
	/** @var string|null Base name of all Binary Log files */
	private $binLog;
	/** @var int[]|null Binary Log position tuple (index number, event number) */
	private $logPos;
	/** @var string[] Map of (server_uuid/gtid_domain_id => GTID) */
	private $gtids = [];
	/** @var int|null Active GTID domain ID */
	private $activeDomain;
	/** @var int|null ID of the server were DB writes originate */
	private $activeServerId;
	/** @var string|null UUID of the server were DB writes originate */
	private $activeServerUUID;
	/** @var float UNIX timestamp */
	private $asOfTime = 0.0;

	private const BINARY_LOG = 'binary-log';
	private const GTID_MARIA = 'gtid-maria';
	private const GTID_MYSQL = 'gtid-mysql';

	/** @var int Key name of the binary log index number of a position tuple */
	public const CORD_INDEX = 0;
	/** @var int Key name of the binary log event number of a position tuple */
	public const CORD_EVENT = 1;

	/**
	 * @param string $position One of (comma separated GTID list, <binlog file>/<integer>)
	 * @param float $asOfTime UNIX timestamp
	 */
	public function __construct( $position, $asOfTime ) {
		$this->init( $position, $asOfTime );
	}

	/**
	 * @param string $position
	 * @param float $asOfTime
	 */
	protected function init( $position, $asOfTime ) {
		$m = [];
		if ( preg_match( '!^(.+)\.(\d+)/(\d+)$!', $position, $m ) ) {
			$this->binLog = $m[1]; // ideally something like host name
			$this->logPos = [ self::CORD_INDEX => (int)$m[2], self::CORD_EVENT => (int)$m[3] ];
			$this->style = self::BINARY_LOG;
		} else {
			$gtids = array_filter( array_map( 'trim', explode( ',', $position ) ) );
			foreach ( $gtids as $gtid ) {
				$components = self::parseGTID( $gtid );
				if ( !$components ) {
					throw new InvalidArgumentException( "Invalid GTID '$gtid'." );
				}

				list( $domain, $pos ) = $components;
				if ( isset( $this->gtids[$domain] ) ) {
					// For MySQL, handle the case where some past issue caused a gap in the
					// executed GTID set, e.g. [last_purged+1,N-1] and [N+1,N+2+K]. Ignore the
					// gap by using the GTID with the highest ending sequence number.
					list( , $otherPos ) = self::parseGTID( $this->gtids[$domain] );
					if ( $pos > $otherPos ) {
						$this->gtids[$domain] = $gtid;
					}
				} else {
					$this->gtids[$domain] = $gtid;
				}

				if ( is_int( $domain ) ) {
					$this->style = self::GTID_MARIA; // gtid_domain_id
				} else {
					$this->style = self::GTID_MYSQL; // server_uuid
				}
			}
			if ( !$this->gtids ) {
				throw new InvalidArgumentException( "GTID set cannot be empty." );
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
		$thisPosByDomain = $this->getActiveGtidCoordinates();
		$thatPosByDomain = $pos->getActiveGtidCoordinates();
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
		$thisPosDomains = array_keys( $this->getActiveGtidCoordinates() );
		$thatPosDomains = array_keys( $pos->getActiveGtidCoordinates() );
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
	 * @return string|null Base name of binary log files
	 * @since 1.31
	 */
	public function getLogName() {
		return $this->gtids ? null : $this->binLog;
	}

	/**
	 * @return int[]|null Tuple of (binary log file number, event number)
	 * @since 1.31
	 */
	public function getLogPosition() {
		return $this->gtids ? null : $this->logPos;
	}

	/**
	 * @return string|null Name of the binary log file for this position
	 * @since 1.31
	 */
	public function getLogFile() {
		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
		return $this->gtids ? null : "{$this->binLog}.{$this->logPos[self::CORD_INDEX]}";
	}

	/**
	 * @return string[] Map of (server_uuid/gtid_domain_id => GTID)
	 * @since 1.31
	 */
	public function getGTIDs() {
		return $this->gtids;
	}

	/**
	 * Set the GTID domain known to be used in new commits on a replication stream of interest
	 *
	 * This makes getRelevantActiveGTIDs() filter out GTIDs from other domains
	 *
	 * @see MySQLMasterPos::getRelevantActiveGTIDs()
	 * @see https://mariadb.com/kb/en/library/gtid/#gtid_domain_id
	 *
	 * @param int|null $id @@gtid_domain_id of the active replication stream
	 * @return MySQLMasterPos This instance (since 1.34)
	 * @since 1.31
	 */
	public function setActiveDomain( $id ) {
		$this->activeDomain = (int)$id;

		return $this;
	}

	/**
	 * Set the server ID known to be used in new commits on a replication stream of interest
	 *
	 * This makes getRelevantActiveGTIDs() filter out GTIDs from other origin servers
	 *
	 * @see MySQLMasterPos::getRelevantActiveGTIDs()
	 *
	 * @param int|null $id @@server_id of the server were writes originate
	 * @return MySQLMasterPos This instance (since 1.34)
	 * @since 1.31
	 */
	public function setActiveOriginServerId( $id ) {
		$this->activeServerId = (int)$id;

		return $this;
	}

	/**
	 * Set the server UUID known to be used in new commits on a replication stream of interest
	 *
	 * This makes getRelevantActiveGTIDs() filter out GTIDs from other origin servers
	 *
	 * @see MySQLMasterPos::getRelevantActiveGTIDs()
	 *
	 * @param string|null $id @@server_uuid of the server were writes originate
	 * @return MySQLMasterPos This instance (since 1.34)
	 * @since 1.31
	 */
	public function setActiveOriginServerUUID( $id ) {
		$this->activeServerUUID = $id;

		return $this;
	}

	/**
	 * @param MySQLMasterPos $pos
	 * @param MySQLMasterPos $refPos
	 * @return string[] List of active GTIDs from $pos that have domains in $refPos
	 * @since 1.34
	 */
	public static function getRelevantActiveGTIDs( MySQLMasterPos $pos, MySQLMasterPos $refPos ) {
		return array_values( array_intersect_key(
			$pos->gtids,
			$pos->getActiveGtidCoordinates(),
			$refPos->gtids
		) );
	}

	/**
	 * @see https://mariadb.com/kb/en/mariadb/gtid
	 * @see https://dev.mysql.com/doc/refman/5.6/en/replication-gtids-concepts.html
	 * @return array Map of (server_uuid/gtid_domain_id => integer position); possibly empty
	 */
	protected function getActiveGtidCoordinates() {
		$gtidInfos = [];

		foreach ( $this->gtids as $domain => $gtid ) {
			list( $domain, $pos, $server ) = self::parseGTID( $gtid );

			$ignore = false;
			// Filter out GTIDs from non-active replication domains
			if ( $this->style === self::GTID_MARIA && $this->activeDomain !== null ) {
				$ignore = $ignore || ( $domain !== $this->activeDomain );
			}
			// Likewise for GTIDs from non-active replication origin servers
			if ( $this->style === self::GTID_MARIA && $this->activeServerId !== null ) {
				$ignore = $ignore || ( $server !== $this->activeServerId );
			} elseif ( $this->style === self::GTID_MYSQL && $this->activeServerUUID !== null ) {
				$ignore = $ignore || ( $server !== $this->activeServerUUID );
			}

			if ( !$ignore ) {
				$gtidInfos[$domain] = $pos;
			}
		}

		return $gtidInfos;
	}

	/**
	 * @param string $id GTID
	 * @return array|null [domain ID or server UUID, sequence number, server ID/UUID] or null
	 */
	protected static function parseGTID( $id ) {
		$m = [];
		if ( preg_match( '!^(\d+)-(\d+)-(\d+)$!', $id, $m ) ) {
			// MariaDB style: <domain>-<server id>-<sequence number>
			return [ (int)$m[1], (int)$m[3], (int)$m[2] ];
		} elseif ( preg_match( '!^(\w{8}-\w{4}-\w{4}-\w{4}-\w{12}):(?:\d+-|)(\d+)$!', $id, $m ) ) {
			// MySQL style: <server UUID>:<sequence number>-<sequence number>
			// Normally, the first number should reflect the point (gtid_purged) where older
			// binary logs where purged to save space. When doing comparisons, it may as well
			// be 1 in that case. Assume that this is generally the situation.
			return [ $m[1], (int)$m[2], $m[1] ];
		}

		return null;
	}

	/**
	 * @see https://dev.mysql.com/doc/refman/5.7/en/show-master-status.html
	 * @see https://dev.mysql.com/doc/refman/5.7/en/show-slave-status.html
	 * @return array|bool Map of (binlog:<string>, pos:(<integer>, <integer>)) or false
	 */
	protected function getBinlogCoordinates() {
		return ( $this->binLog !== null && $this->logPos !== null )
			? [ 'binlog' => $this->binLog, 'pos' => $this->logPos ]
			: false;
	}

	public function serialize() {
		return serialize( [
			'position' => $this->__toString(),
			'activeDomain' => $this->activeDomain,
			'activeServerId' => $this->activeServerId,
			'activeServerUUID' => $this->activeServerUUID,
			'asOfTime' => $this->asOfTime
		] );
	}

	public function unserialize( $serialized ) {
		$data = unserialize( $serialized );
		if ( !is_array( $data ) ) {
			throw new UnexpectedValueException( __METHOD__ . ": cannot unserialize position" );
		}

		$this->init( $data['position'], $data['asOfTime'] );
		if ( isset( $data['activeDomain'] ) ) {
			$this->setActiveDomain( $data['activeDomain'] );
		}
		if ( isset( $data['activeServerId'] ) ) {
			$this->setActiveOriginServerId( $data['activeServerId'] );
		}
		if ( isset( $data['activeServerUUID'] ) ) {
			$this->setActiveOriginServerUUID( $data['activeServerUUID'] );
		}
	}

	/**
	 * @return string GTID set or <binary log file>/<position> (e.g db1034-bin.000976/843431247)
	 */
	public function __toString() {
		return $this->gtids
			? implode( ',', $this->gtids )
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
			: $this->getLogFile() . "/{$this->logPos[self::CORD_EVENT]}";
	}
}
