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

class Block
{
	/* public*/ var $mAddress, $mUser, $mBy, $mReason, $mTimestamp, $mAuto, $mId, $mExpiry;
	/* private */ var $mNetworkBits, $mIntegerAddr;
	
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
	function load( $address = "", $user = 0, $killExpired = true ) 
	{
		global $wgLoadBalancer;
                $fname = 'Block::load';
		$ret = false;
		$killed = false;
		
		if ( 0 == $user && $address=="" ) {
			$sql = "SELECT * from ipblocks";
		} elseif ($address=="") {
			$sql = "SELECT * FROM ipblocks WHERE ipb_user={$user}";
		} elseif ($user=="") {
			$sql = "SELECT * FROM ipblocks WHERE ipb_address='" . wfStrencode( $address ) . "'";
		} else {
			$sql = "SELECT * FROM ipblocks WHERE (ipb_address='" . wfStrencode( $address ) . 
				"' OR ipb_user={$user})";
		}

		$wgLoadBalancer->force(-1);
                $res = wfQuery( $sql, DB_READ, $fname );
		$wgLoadBalancer->force(0);
                if ( 0 == wfNumRows( $res ) ) {
			# User is not blocked
			$this->clear();
		} else {
			# Get first block
			$row = wfFetchObject( $res );
			$this->initFromRow( $row );

			if ( $killExpired ) {
				# If requested, delete expired rows
				do {
					$killed = $this->deleteIfExpired();
					if ( $killed ) {
						$row = wfFetchObject( $res );
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
		wfFreeResult( $res );
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
	/*static*/ function enumBlocks( $callback, $tag, $killExpired = true ) 
	{
		$sql = 'SELECT * FROM ipblocks ORDER BY ipb_timestamp DESC';
		$res = wfQuery( $sql, DB_READ, 'Block::enumBans' );
		$block = new Block();

		while ( $row = wfFetchObject( $res ) ) {
			$block->initFromRow( $row );
			if ( $killExpired ) {
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
		if ( $this->mAddress == "" ) {
			$sql = "DELETE FROM ipblocks WHERE ipb_id={$this->mId}";
		} else {
			$sql = "DELETE FROM ipblocks WHERE ipb_address='" .
				wfStrencode( $this->mAddress ) . "'";
		}
		wfQuery( $sql, DB_WRITE, 'Block::delete' );

		$this->clearCache();
	}

	function insert() 
	{
		$sql = 'INSERT INTO ipblocks ' .
		  '(ipb_address, ipb_user, ipb_by, ipb_reason, ipb_timestamp, ipb_auto, ipb_expiry )' . 
		  "VALUES ('" . wfStrencode( $this->mAddress ) . "', {$this->mUser}, {$this->mBy}, '" . 
		  wfStrencode( $this->mReason ) . "','{$this->mTimestamp}', {$this->mAuto}, '{$this->mExpiry}')";
		wfQuery( $sql, DB_WRITE, 'Block::insert' );

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

			wfQuery( 'UPDATE ipblocks SET ' .
				"ipb_timestamp='" . $this->mTimestamp . "', " .
				"ipb_expiry='" . $this->mExpiry . "' " .
				"WHERE ipb_address='" . wfStrencode( $this->mAddress ) . "'", DB_WRITE, 'Block::updateTimestamp' );
			
			$this->clearCache();
		}
	}

	/* private */ function clearCache()
	{
		global $wgBlockCache;
		if ( is_object( $wgBlockCache ) ) {
			$wgBlockCache->clear();
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
