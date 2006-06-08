<?php
/**
 * Blocks and bans object
 * @package MediaWiki
 */

/**
 * The block class
 * All the functions in this class assume the object is either explicitly
 * loaded or filled. It is not load-on-demand. There are no accessors.
 *
 * To use delete(), you only need to fill $mAddress
 * Globals used: $wgAutoblockExpiry, $wgAntiLockFlags
 *
 * @todo This could be used everywhere, but it isn't.
 * @package MediaWiki
 */
class Block
{
	/* public*/ var $mAddress, $mUser, $mBy, $mReason, $mTimestamp, $mAuto, $mId, $mExpiry,
		            $mRangeStart, $mRangeEnd;
	/* private */ var $mNetworkBits, $mIntegerAddr, $mForUpdate, $mFromMaster, $mByName;
	
	const EB_KEEP_EXPIRED = 1;
	const EB_FOR_UPDATE = 2;
	const EB_RANGE_ONLY = 4;

	function Block( $address = '', $user = '', $by = 0, $reason = '',
		$timestamp = '' , $auto = 0, $expiry = '' )
	{
		$this->mAddress = $address;
		$this->mUser = $user;
		$this->mBy = $by;
		$this->mReason = $reason;
		$this->mTimestamp = wfTimestamp(TS_MW,$timestamp);
		$this->mAuto = $auto;
		if( empty( $expiry ) ) {
			$this->mExpiry = $expiry;
		} else {
			$this->mExpiry = wfTimestamp( TS_MW, $expiry );
		}

		$this->mForUpdate = false;
		$this->mFromMaster = false;
		$this->mByName = false;
		$this->initialiseRange();
	}

	/*static*/ function newFromDB( $address, $user = 0, $killExpired = true )
	{
		$ban = new Block();
		$ban->load( $address, $user, $killExpired );
		return $ban;
	}

	function clear()
	{
		$this->mAddress = $this->mReason = $this->mTimestamp = '';
		$this->mUser = $this->mBy = 0;
		$this->mByName = false;

	}

	/**
	 * Get the DB object and set the reference parameter to the query options
	 */
	function &getDBOptions( &$options )
	{
		global $wgAntiLockFlags;
		if ( $this->mForUpdate || $this->mFromMaster ) {
			$db =& wfGetDB( DB_MASTER );
			if ( !$this->mForUpdate || ($wgAntiLockFlags & ALF_NO_BLOCK_LOCK) ) {
				$options = '';
			} else {
				$options = 'FOR UPDATE';
			}
		} else {
			$db =& wfGetDB( DB_SLAVE );
			$options = '';
		}
		return $db;
	}

	/**
	 * Get a ban from the DB, with either the given address or the given username
	 */
	function load( $address = '', $user = 0, $killExpired = true )
	{
		$fname = 'Block::load';
		wfDebug( "Block::load: '$address', '$user', $killExpired\n" );

		$options = '';
		$db =& $this->getDBOptions( $options );

		$ret = false;
		$killed = false;
		$ipblocks = $db->tableName( 'ipblocks' );

		if ( 0 == $user && $address == '' ) {
			# Invalid user specification, not blocked
			$this->clear();
			return false;
		} elseif ( $address == '' ) {
			$sql = "SELECT * FROM $ipblocks WHERE ipb_user={$user} $options";
		} elseif ( $user == '' ) {
			$sql = "SELECT * FROM $ipblocks WHERE ipb_address=" . $db->addQuotes( $address ) . " $options";
		} elseif ( $options == '' ) {
			# If there are no options (e.g. FOR UPDATE), use a UNION
			# so that the query can make efficient use of indices
			$sql = "SELECT * FROM $ipblocks WHERE ipb_address='" . $db->strencode( $address ) .
				"' UNION SELECT * FROM $ipblocks WHERE ipb_user={$user}";
		} else {
			# If there are options, a UNION can not be used, use one
			# SELECT instead. Will do a full table scan.
			$sql = "SELECT * FROM $ipblocks WHERE (ipb_address='" . $db->strencode( $address ) .
				"' OR ipb_user={$user}) $options";
		}

		$res = $db->query( $sql, $fname );
		if ( 0 != $db->numRows( $res ) ) {
			# Get first block
			$row = $db->fetchObject( $res );
			$this->initFromRow( $row );

			if ( $killExpired ) {
				# If requested, delete expired rows
				do {
					$killed = $this->deleteIfExpired();
					if ( $killed ) {
						$row = $db->fetchObject( $res );
						if ( $row ) {
							$this->initFromRow( $row );
						}
					}
				} while ( $killed && $row );

				# If there were any left after the killing finished, return true
				if ( !$row ) {
					$ret = false;
					$this->clear();
				} else {
					$ret = true;
				}
			} else {
				$ret = true;
			}
		}
		$db->freeResult( $res );

		# No blocks found yet? Try looking for range blocks
		if ( !$ret && $address != '' ) {
			$ret = $this->loadRange( $address, $killExpired );
		}
		if ( !$ret ) {
			$this->clear();
		}

		return $ret;
	}

	/**
	 * Search the database for any range blocks matching the given address, and
	 * load the row if one is found.
	 */
	function loadRange( $address, $killExpired = true )
	{
		$fname = 'Block::loadRange';

		$iaddr = wfIP2Hex( $address );
		if ( $iaddr === false ) {
			# Invalid address
			return false;
		}

		# Only scan ranges which start in this /16, this improves search speed
		# Blocks should not cross a /16 boundary.
		$range = substr( $iaddr, 0, 4 );

		$options = '';
		$db =& $this->getDBOptions( $options );
		$ipblocks = $db->tableName( 'ipblocks' );
		$sql = "SELECT * FROM $ipblocks WHERE ipb_range_start LIKE '$range%' ".
			"AND ipb_range_start <= '$iaddr' AND ipb_range_end >= '$iaddr' $options";
		$res = $db->query( $sql, $fname );
		$row = $db->fetchObject( $res );

		$success = false;
		if ( $row ) {
			# Found a row, initialise this object
			$this->initFromRow( $row );

			# Is it expired?
			if ( !$killExpired || !$this->deleteIfExpired() ) {
				# No, return true
				$success = true;
			}
		}

		$db->freeResult( $res );
		return $success;
	}

	/**
	 * Determine if a given integer IPv4 address is in a given CIDR network
	 */
	function isAddressInRange( $addr, $range ) {
		list( $network, $bits ) = wfParseCIDR( $range );
		if ( $network !== false && $addr >> ( 32 - $bits ) == $network >> ( 32 - $bits ) ) {
			return true;
		} else {
			return false;
		}
	}

	function initFromRow( $row )
	{
		$this->mAddress = $row->ipb_address;
		$this->mReason = $row->ipb_reason;
		$this->mTimestamp = wfTimestamp(TS_MW,$row->ipb_timestamp);
		$this->mUser = $row->ipb_user;
		$this->mBy = $row->ipb_by;
		$this->mAuto = $row->ipb_auto;
		$this->mId = $row->ipb_id;
		$this->mExpiry = $row->ipb_expiry ?
			wfTimestamp(TS_MW,$row->ipb_expiry) :
			$row->ipb_expiry;
		if ( isset( $row->user_name ) ) {
			$this->mByName = $row->user_name;
		} else {
			$this->mByName = false;
		}
		$this->mRangeStart = $row->ipb_range_start;
		$this->mRangeEnd = $row->ipb_range_end;
	}

	function initialiseRange()
	{
		$this->mRangeStart = '';
		$this->mRangeEnd = '';
		if ( $this->mUser == 0 ) {
			list( $network, $bits ) = wfParseCIDR( $this->mAddress );
			if ( $network !== false ) {
				$this->mRangeStart = sprintf( '%08X', $network );
				$this->mRangeEnd = sprintf( '%08X', $network + (1 << (32 - $bits)) - 1 );
			}
		}
	}

	/**
	 * Callback with a Block object for every block
	 * @return integer number of blocks;
	 */
	/*static*/ function enumBlocks( $callback, $tag, $flags = 0 )
	{
		global $wgAntiLockFlags;

		$block = new Block();
		if ( $flags & Block::EB_FOR_UPDATE ) {
			$db =& wfGetDB( DB_MASTER );
			if ( $wgAntiLockFlags & ALF_NO_BLOCK_LOCK ) {
				$options = '';
			} else {
				$options = 'FOR UPDATE';
			}
			$block->forUpdate( true );
		} else {
			$db =& wfGetDB( DB_SLAVE );
			$options = '';
		}
		if ( $flags & Block::EB_RANGE_ONLY ) {
			$cond = " AND ipb_range_start <> ''";
		} else {
			$cond = '';
		}

		$now = wfTimestampNow();

		extract( $db->tableNames( 'ipblocks', 'user' ) );

		$sql = "SELECT $ipblocks.*,user_name FROM $ipblocks,$user " .
			"WHERE user_id=ipb_by $cond ORDER BY ipb_timestamp DESC $options";
		$res = $db->query( $sql, 'Block::enumBlocks' );
		$num_rows = $db->numRows( $res );

		while ( $row = $db->fetchObject( $res ) ) {
			$block->initFromRow( $row );
			if ( ( $flags & Block::EB_RANGE_ONLY ) && $block->mRangeStart == '' ) {
				continue;
			}

			if ( !( $flags & Block::EB_KEEP_EXPIRED ) ) {
				if ( $block->mExpiry && $now > $block->mExpiry ) {
					$block->delete();
				} else {
					call_user_func( $callback, $block, $tag );
				}
			} else {
				call_user_func( $callback, $block, $tag );
			}
		}
		wfFreeResult( $res );
		return $num_rows;
	}

	function delete()
	{
		$fname = 'Block::delete';
		if (wfReadOnly()) {
			return;
		}
		$dbw =& wfGetDB( DB_MASTER );

		if ( $this->mAddress == '' ) {
			$condition = array( 'ipb_id' => $this->mId );
		} else {
			$condition = array( 'ipb_address' => $this->mAddress );
		}
		return( $dbw->delete( 'ipblocks', $condition, $fname ) > 0 ? true : false );
	}

	function insert()
	{
		wfDebug( "Block::insert; timestamp {$this->mTimestamp}\n" );
		$dbw =& wfGetDB( DB_MASTER );
		$ipb_id = $dbw->nextSequenceValue('ipblocks_ipb_id_val');
		$dbw->insert( 'ipblocks',
			array(
				'ipb_id' => $ipb_id,
				'ipb_address' => $this->mAddress,
				'ipb_user' => $this->mUser,
				'ipb_by' => $this->mBy,
				'ipb_reason' => $this->mReason,
				'ipb_timestamp' => $dbw->timestamp($this->mTimestamp),
				'ipb_auto' => $this->mAuto,
				'ipb_expiry' => $this->mExpiry ?
					$dbw->timestamp($this->mExpiry) :
					$this->mExpiry,
				'ipb_range_start' => $this->mRangeStart,
				'ipb_range_end' => $this->mRangeEnd,
			), 'Block::insert'
		);
	}

	function deleteIfExpired()
	{
		$fname = 'Block::deleteIfExpired';
		wfProfileIn( $fname );
		if ( $this->isExpired() ) {
			wfDebug( "Block::deleteIfExpired() -- deleting\n" );
			$this->delete();
			$retVal = true;
		} else {
			wfDebug( "Block::deleteIfExpired() -- not expired\n" );
			$retVal = false;
		}
		wfProfileOut( $fname );
		return $retVal;
	}

	function isExpired()
	{
		wfDebug( "Block::isExpired() checking current " . wfTimestampNow() . " vs $this->mExpiry\n" );
		if ( !$this->mExpiry ) {
			return false;
		} else {
			return wfTimestampNow() > $this->mExpiry;
		}
	}

	function isValid()
	{
		return $this->mAddress != '';
	}

	function updateTimestamp()
	{
		if ( $this->mAuto ) {
			$this->mTimestamp = wfTimestamp();
			$this->mExpiry = Block::getAutoblockExpiry( $this->mTimestamp );

			$dbw =& wfGetDB( DB_MASTER );
			$dbw->update( 'ipblocks',
				array( /* SET */
					'ipb_timestamp' => $dbw->timestamp($this->mTimestamp),
					'ipb_expiry' => $dbw->timestamp($this->mExpiry),
				), array( /* WHERE */
					'ipb_address' => $this->mAddress
				), 'Block::updateTimestamp'
			);
		}
	}

	/*
	function getIntegerAddr()
	{
		return $this->mIntegerAddr;
	}

	function getNetworkBits()
	{
		return $this->mNetworkBits;
	}*/

	function getByName()
	{
		if ( $this->mByName === false ) {
			$this->mByName = User::whoIs( $this->mBy );
		}
		return $this->mByName;
	}

	function forUpdate( $x = NULL ) {
		return wfSetVar( $this->mForUpdate, $x );
	}

	function fromMaster( $x = NULL ) {
		return wfSetVar( $this->mFromMaster, $x );
	}

	/* static */ function getAutoblockExpiry( $timestamp )
	{
		global $wgAutoblockExpiry;
		return wfTimestamp( TS_MW, wfTimestamp( TS_UNIX, $timestamp ) + $wgAutoblockExpiry );
	}

	/* static */ function normaliseRange( $range )
	{
		$parts = explode( '/', $range );
		if ( count( $parts ) == 2 ) {
			$shift = 32 - $parts[1];
			$ipint = wfIP2Unsigned( $parts[0] );
			$ipint = $ipint >> $shift << $shift;
			$newip = long2ip( $ipint );
			$range = "$newip/{$parts[1]}";
		}
		return $range;
	}

}
?>
