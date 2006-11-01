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
 * Globals used: $wgAutoblockExpiry, $wgAntiLockFlags
 *
 * @todo This could be used everywhere, but it isn't.
 * @package MediaWiki
 */
class Block
{
	/* public*/ var $mAddress, $mUser, $mBy, $mReason, $mTimestamp, $mAuto, $mId, $mExpiry,
		            $mRangeStart, $mRangeEnd, $mAnonOnly;
	/* private */ var $mNetworkBits, $mIntegerAddr, $mForUpdate, $mFromMaster, $mByName;
	
	const EB_KEEP_EXPIRED = 1;
	const EB_FOR_UPDATE = 2;
	const EB_RANGE_ONLY = 4;

	function Block( $address = '', $user = 0, $by = 0, $reason = '',
		$timestamp = '' , $auto = 0, $expiry = '', $anonOnly = 0, $createAccount = 0 )
	{
		$this->mId = 0;
		$this->mAddress = $address;
		$this->mUser = $user;
		$this->mBy = $by;
		$this->mReason = $reason;
		$this->mTimestamp = wfTimestamp(TS_MW,$timestamp);
		$this->mAuto = $auto;
		$this->mAnonOnly = $anonOnly;
		$this->mCreateAccount = $createAccount;
		$this->mExpiry = self::decodeExpiry( $expiry );

		$this->mForUpdate = false;
		$this->mFromMaster = false;
		$this->mByName = false;
		$this->initialiseRange();
	}

	static function newFromDB( $address, $user = 0, $killExpired = true )
	{
		$block = new Block();
		$block->load( $address, $user, $killExpired );
		if ( $block->isValid() ) {
			return $block;
		} else {
			return null;
		}
	}

	static function newFromID( $id ) 
	{
		$dbr =& wfGetDB( DB_SLAVE );
		$res = $dbr->resultObject( $dbr->select( 'ipblocks', '*', 
			array( 'ipb_id' => $id ), __METHOD__ ) );
		$block = new Block;
		if ( $block->loadFromResult( $res ) ) {
			return $block;
		} else {
			return null;
		}
	}

	function clear()
	{
		$this->mAddress = $this->mReason = $this->mTimestamp = '';
		$this->mId = $this->mAnonOnly = $this->mCreateAccount = 
			$this->mAuto = $this->mUser = $this->mBy = 0;
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
				$options = array();
			} else {
				$options = array( 'FOR UPDATE' );
			}
		} else {
			$db =& wfGetDB( DB_SLAVE );
			$options = array();
		}
		return $db;
	}

	/**
	 * Get a ban from the DB, with either the given address or the given username
	 *
	 * @param string $address The IP address of the user, or blank to skip IP blocks
	 * @param integer $user The user ID, or zero for anonymous users
	 * @param bool $killExpired Whether to delete expired rows while loading
	 *
	 */
	function load( $address = '', $user = 0, $killExpired = true )
	{
		wfDebug( "Block::load: '$address', '$user', $killExpired\n" );

		$options = array();
		$db =& $this->getDBOptions( $options );

		$ret = false;
		$killed = false;

		if ( 0 == $user && $address == '' ) {
			# Invalid user specification, not blocked
			$this->clear();
			return false;
		}

		# Try user block
		if ( $user ) {
			$res = $db->resultObject( $db->select( 'ipblocks', '*', array( 'ipb_user' => $user ), 
				__METHOD__, $options ) );
			if ( $this->loadFromResult( $res, $killExpired ) ) {
				return true;
			}
		}

		# Try IP block
		# TODO: improve performance by merging this query with the autoblock one
		# Slightly tricky while handling killExpired as well
		if ( $address ) {
			$conds = array( 'ipb_address' => $address, 'ipb_auto' => 0 );
			$res = $db->resultObject( $db->select( 'ipblocks', '*', $conds, __METHOD__, $options ) );
			if ( $this->loadFromResult( $res, $killExpired ) ) {
				if ( $user && $this->mAnonOnly ) {
					# Block is marked anon-only
					# Whitelist this IP address against autoblocks and range blocks
					$this->clear();
					return false;
				} else {
					return true;
				}
			}
		}

		# Try range block
		if ( $this->loadRange( $address, $killExpired, $user == 0 ) ) {
			if ( $user && $this->mAnonOnly ) {
				$this->clear();
				return false;
			} else {
				return true;
			}
		}

		# Try autoblock
		if ( $address ) {
			$conds = array( 'ipb_address' => $address, 'ipb_auto' => 1 );
			if ( $user ) {
				$conds['ipb_anon_only'] = 0;
			}
			$res = $db->resultObject( $db->select( 'ipblocks', '*', $conds, __METHOD__, $options ) );
			if ( $this->loadFromResult( $res, $killExpired ) ) {
				return true;
			}
		}
		
		# Give up
		$this->clear();
		return false;
	}

	/**
	 * Fill in member variables from a result wrapper
	 */
	function loadFromResult( ResultWrapper $res, $killExpired = true ) {
		$ret = false;
		if ( 0 != $res->numRows() ) {
			# Get first block
			$row = $res->fetchObject();
			$this->initFromRow( $row );

			if ( $killExpired ) {
				# If requested, delete expired rows
				do {
					$killed = $this->deleteIfExpired();
					if ( $killed ) {
						$row = $res->fetchObject();
						if ( $row ) {
							$this->initFromRow( $row );
						}
					}
				} while ( $killed && $row );

				# If there were any left after the killing finished, return true
				if ( $row ) {
					$ret = true;
				}
			} else {
				$ret = true;
			}
		}
		$res->free();
		return $ret;
	}

	/**
	 * Search the database for any range blocks matching the given address, and
	 * load the row if one is found.
	 */
	function loadRange( $address, $killExpired = true )
	{
		$iaddr = IP::toHex( $address );
		if ( $iaddr === false ) {
			# Invalid address
			return false;
		}

		# Only scan ranges which start in this /16, this improves search speed
		# Blocks should not cross a /16 boundary.
		$range = substr( $iaddr, 0, 4 );

		$options = array();
		$db =& $this->getDBOptions( $options );
		$conds = array(
			"ipb_range_start LIKE '$range%'",
			"ipb_range_start <= '$iaddr'",
			"ipb_range_end >= '$iaddr'"
		);

		$res = $db->resultObject( $db->select( 'ipblocks', '*', $conds, __METHOD__, $options ) );
		$success = $this->loadFromResult( $res, $killExpired );
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
		$this->mAnonOnly = $row->ipb_anon_only;
		$this->mCreateAccount = $row->ipb_create_account;
		$this->mId = $row->ipb_id;
		$this->mExpiry = self::decodeExpiry( $row->ipb_expiry );
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
		if (wfReadOnly()) {
			return false;
		}
		if ( !$this->mId ) {
			throw new MWException( "Block::delete() now requires that the mId member be filled\n" );
		}

		$dbw =& wfGetDB( DB_MASTER );
		$dbw->delete( 'ipblocks', array( 'ipb_id' => $this->mId ), __METHOD__ );
		return $dbw->affectedRows() > 0;
	}

	function insert()
	{
		wfDebug( "Block::insert; timestamp {$this->mTimestamp}\n" );
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->begin();

		# Unset ipb_anon_only for user blocks, makes no sense
		if ( $this->mUser ) {
			$this->mAnonOnly = 0;
		}

		# Don't collide with expired blocks
		Block::purgeExpired();
		
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
				'ipb_anon_only' => $this->mAnonOnly,
				'ipb_create_account' => $this->mCreateAccount,
				'ipb_expiry' => self::encodeExpiry( $this->mExpiry, $dbw ),
				'ipb_range_start' => $this->mRangeStart,
				'ipb_range_end' => $this->mRangeEnd,
			), 'Block::insert', array( 'IGNORE' )
		);
		$affected = $dbw->affectedRows();
		$dbw->commit();
		return $affected;
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

	function getRedactedName() {
		if ( $this->mAuto ) {
			return '#' . $this->mId;
		} else {
			return $this->mAddress;
		}
	}
	
	/**
	 * Encode expiry for DB
	 */
	static function encodeExpiry( $expiry, $db ) {
		if ( $expiry == '' || $expiry == Block::infinity() ) {
			return Block::infinity();
		} else {
			return $db->timestamp( $expiry );
		}
	}

	/** 
	 * Decode expiry which has come from the DB
	 */
	static function decodeExpiry( $expiry ) {
		if ( $expiry == '' || $expiry == Block::infinity() ) {
			return Block::infinity();
		} else {
			return wfTimestamp( TS_MW, $expiry );
		}
	}
	
	static function getAutoblockExpiry( $timestamp )
	{
		global $wgAutoblockExpiry;
		return wfTimestamp( TS_MW, wfTimestamp( TS_UNIX, $timestamp ) + $wgAutoblockExpiry );
	}

	static function normaliseRange( $range )
	{
		$parts = explode( '/', $range );
		if ( count( $parts ) == 2 ) {
			$shift = 32 - $parts[1];
			$ipint = IP::toUnsigned( $parts[0] );
			$ipint = $ipint >> $shift << $shift;
			$newip = long2ip( $ipint );
			$range = "$newip/{$parts[1]}";
		}
		return $range;
	}

	/** 
	 * Purge expired blocks from the ipblocks table
	 */
	static function purgeExpired() {
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->delete( 'ipblocks', array( 'ipb_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ), __METHOD__ );
	}

	static function infinity() {
		# This is a special keyword for timestamps in PostgreSQL, and 
		# works with CHAR(14) as well because "i" sorts after all numbers.		
		return 'infinity';

		/*
		static $infinity;
		if ( !isset( $infinity ) ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$infinity = $dbr->bigTimestamp();
		}
		return $infinity;
		 */
	}

}
?>
