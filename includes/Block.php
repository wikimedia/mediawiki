<?
# Blocks and bans object

#TODO: This could be used everywhere, but it isn't.

# All the functions in this class assume the object is either explicitly 
# loaded or filled. It is not load-on-demand.

# To use delete(), you only need to fill $mAddress

class Block
{
	var $mAddress, $mUser, $mBy, $mReason, $mTimestamp;
	
	function Block( $address = "", $user = "", $by = 0, $reason = "", $timestamp = "" ) 
	{
		$this->mAddress = $address;
		$this->mUser = $user;
		$this->mBy = $by;
		$this->mReason = $reason;
		$this->mTimestamp = $timestamp;
	}

	/*static*/ function newFromDB( $address, $user = 0, $killExpired = true ) 
	{
		$ban = new Block();
		$ban->load( $address, $user, $killExpired );
		return $ban;
	}
		
	function clear()
	{
		$mAddress = $mReason = $mTimestamp = "";
		$mUser = $mBy = 0;
	}

	# Get a ban from the DB, with either the given address or the given username
	function load( $address, $user = 0, $killExpired = true ) 
	{
		$fname = "Block::load";
		$ret = false;
		$killed = false;
		
		if ( 0 == $user ) {
			$sql = "SELECT * FROM ipblocks WHERE ipb_address='$address'";
		} else {
			$sql = "SELECT * FROM ipblocks WHERE (ipb_address='$address' OR ipb_user={$user})";
		}
		

		$res = wfQuery( $sql, $fname );
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
					$row = wfFetchObject( $res );
				} while ( $killed && $row );
				
				# If there were any left after the killing finished, return true
				if ( $row == false ) {
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
	}	

	# Callback with a Block object for every block
	/*static*/ function enumBlocks( $callback, $tag, $killExpired = true )
	{
		$sql = "SELECT * FROM ipblocks ORDER BY ipb_timestamp";
		$res = wfQuery( $sql, "Block::enumBans" );
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
		wfQuery( "DELETE FROM ipblocks WHERE ipb_address='{$this->mAddress}'", 
			"Block::delete" );
	}

	function insert()
	{
		$sql = "INSERT INTO ipblocks (ipb_address, ipb_user, ipb_by, " .
		  "ipb_reason, ipb_timestamp ) VALUES ('{$this->mAddress}', {$this->mUser}, " .
		  "{$this->mBy}, '" . wfStrencode( $this->mReason ) . "','{$this->mTimestamp}')";
		wfQuery( $sql, "Block::insert" );
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
		global $wgIPBlockExpiration, $wgUserBlockExpiration;
		
		$period = $this->mUser ? $wgUserBlockExpiration : $wgIPBlockExpiration;
		
		# Period==0 means no expiry
		if ( !$period ) { 
			return false;
		}
		$expiry = wfTimestamp2Unix( $this->mTimestamp ) + $period;
		$now = wfTimestamp2Unix( wfTimestampNow() );
		if ( $now > $expiry ) {
			return true;
		} else {
			return false;
		}
	}

	function isValid() 
	{
		return $this->mAddress != "";
	}

	
	function updateTimestamp()
	{
		$sql = "UPDATE ipblocks SET ipb_timestamp='" . wfTimestampNow() . "' WHERE ipb_address='{$this->mAddress}'";
		wfQuery( "UPDATE ipblocks SET ipb_timestamp='" . wfTimestampNow() . 
			"' WHERE ipb_address='{$this->mAddress}'", "Block::updateTimestamp" );
	}
}
?>
