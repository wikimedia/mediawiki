<?php
# Blocks and bans object
#
#TODO: This could be used everywhere, but it isn't.
#
# All the functions in this class assume the object is either explicitly 
# loaded or filled. It is not load-on-demand. There are no accessors.
#
# To use delete(), you only need to fill $mAddress

# Globals used: $wgBlockCache, $wgAutoblockExpiry

define ( 'EB_KEEP_EXPIRED', 1 );
define ( 'EB_FOR_UPDATE', 2 );

class Block
{
	/* public*/ var $mAddress, $mUser, $mBy, $mReason, $mTimestamp, $mAuto, $mId, $mExpiry;
	/* private */ var $mNetworkBits, $mIntegerAddr, $mForUpdate;
	
	function Block( $address = '', $user = '', $by = 0, $reason = '', 
		$timestamp = '' , $auto = 0, $expiry = '' ) 
	{
		$this->mAddress = $address;
		$this->mUser = $user;
		$this->mBy = $by;
		$this->mReason = $reason;
		$this->mTimestamp = $timestamp;
		$this->mAuto = $auto;
		$this->mExpiry = $expiry;
		
		$this->mForUpdate = false;
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
		$mAddress = $mReason = $mTimestamp = '';
		$mUser = $mBy = 0;
	}

	# Get a ban from the DB, with either the given address or the given username
	function load( $address = '', $user = 0, $killExpired = true ) 
	{
		$fname = 'Block::load';

		$ret = false;
		$killed = false;
		if ( $this->forUpdate() ) {
			$db =& wfGetDB( DB_MASTER );
			$options = 'FOR UPDATE';
		} else {
			$db =& wfGetDB( DB_SLAVE );
			$options = '';
		}
		$ipblocks = $db->tableName( 'ipblocks' );

		if ( 0 == $user && $address=='' ) {
			$sql = "SELECT * from $ipblocks $options";
		} elseif ($address=="") {
			$sql = "SELECT * FROM $ipblocks WHERE ipb_user={$user} $options";
		} elseif ($user=="") {
			$sql = "SELECT * FROM $ipblocks WHERE ipb_address='" . $db->strencode( $address ) . "' $options";
		} else {
			$sql = "SELECT * FROM $ipblocks WHERE (ipb_address='" . $db->strencode( $address ) . 
				"' OR ipb_user={$user}) $options";
		}

		$res = $db->query( $sql, $fname );
		if ( 0 == $db->numRows( $res ) ) {
			# User is not blocked
			$this->clear();
		} else {
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
		return $ret;
	}
	
	function initFromRow( $row ) 
	{
		$this->mAddress = $row->ipb_address;
		$this->mReason = $row->ipb_reason;
		$this->mTimestamp = $row->ipb_timestamp;
		$this->mUser = $row->ipb_user;
		$this->mBy = $row->ipb_by;
		$this->mAuto = $row->ipb_auto;
		$this->mId = $row->ipb_id;
		$this->mExpiry = $row->ipb_expiry;

		$this->initialiseRange();
	}	

	function initialiseRange()
	{
		if ( $this->mUser == 0 ) {
			$rangeParts = explode( '/', $this->mAddress );
			if ( count( $rangeParts ) == 2 ) {
				$this->mNetworkBits = $rangeParts[1];
			} else {
				$this->mNetworkBits = 32;
			}
			$this->mIntegerAddr = ip2long( $rangeParts[0] );
		} else {
			$this->mNetworkBits = false;
			$this->mIntegerAddr = false;
		}
	}
	
	# Callback with a Block object for every block
	/*static*/ function enumBlocks( $callback, $tag, $flags = 0 ) 
	{
		$block = new Block();
		if ( $flags & EB_FOR_UPDATE ) {
			$db =& wfGetDB( DB_MASTER );
			$options = 'FOR UPDATE';
			$block->forUpdate( true );
		} else {
			$db =& wfGetDB( DB_SLAVE );
			$options = '';
		}	
		$ipblocks = $db->tableName( 'ipblocks' );
		
		$sql = "SELECT * FROM $ipblocks ORDER BY ipb_timestamp DESC $options";
		$res = $db->query( $sql, 'Block::enumBans' );

		while ( $row = $db->fetchObject( $res ) ) {
			$block->initFromRow( $row );
			if ( !( $flags & EB_KEEP_EXPIRED ) ) {
				if ( !$block->deleteIfExpired() ) {
					$callback( $block, $tag );
				}
			} else {
				$callback( $block, $tag );
			}
		}
		wfFreeResult( $res );
	}

	function delete() 
	{
		$fname = 'Block::delete';
		$dbw =& wfGetDB( DB_MASTER );

		if ( $this->mAddress == '' ) {
			$condition = array( 'ipb_id' => $this->mId );
		} else {
			$condition = array( 'ipb_address' => $this->mAddress );
		}
		$dbw->delete( 'ipblocks', $condition, $fname );
		$this->clearCache();
	}

	function insert() 
	{
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->insertArray( 'ipblocks',
			array(
				'ipb_address' => $this->mAddress,
				'ipb_user' => $this->mUser,
				'ipb_by' => $this->mBy,
				'ipb_reason' => $this->mReason,
				'ipb_timestamp' => $this->mTimestamp,
				'ipb_auto' => $this->mAuto,
				'ipb_expiry' => $this->mExpiry,
			), 'Block::insert' 
		);

		$this->clearCache();
	}

	function deleteIfExpired() 
	{
		if ( $this->isExpired() ) {
			$this->delete();
			return true;
		} else {
			return false;
		}
	}

	function isExpired() 
	{	
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
			$this->mTimestamp = wfTimestampNow();
			$this->mExpiry = Block::getAutoblockExpiry( $this->mTimestamp );

			$dbw =& wfGetDB( DB_MASTER );
			$dbw->updateArray( 'ipblocks', 
				array( /* SET */ 
					'ipb_timestamp' => $this->mTimestamp,
					'ipb_expiry' => $this->mExpiry,
				), array( /* WHERE */
					'ipb_address' => $this->mAddress
				), 'Block::updateTimestamp' 
			);
			
			$this->clearCache();
		}
	}

	/* private */ function clearCache()
	{
		global $wgBlockCache;
		if ( is_object( $wgBlockCache ) ) {
			$wgBlockCache->loadFromDB();
		}
	}
	
	function getIntegerAddr()
	{
		return $this->mIntegerAddr;
	}
	
	function getNetworkBits()
	{
		return $this->mNetworkBits;
	}

	function forUpdate( $x = NULL ) {
		return wfSetVar( $this->mForUpdate, $x );
	}

	/* static */ function getAutoblockExpiry( $timestamp )
	{
		global $wgAutoblockExpiry;
		return wfUnix2Timestamp( wfTimestamp2Unix( $timestamp ) + $wgAutoblockExpiry );
	}

	/* static */ function normaliseRange( $range )
	{
		$parts = explode( '/', $range );
		if ( count( $parts ) == 2 ) {
			$shift = 32 - $parts[1];
			$ipint = ip2long( $parts[0] );
			$ipint = $ipint >> $shift << $shift;
			$newip = long2ip( $ipint );
			$range = "$newip/{$parts[1]}";
		}
		return $range;
	}

}
?>
