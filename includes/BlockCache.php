<?php

# Object for fast lookup of IP blocks
# Represents a memcached value, and in some sense, the entire ipblocks table

class BlockCache
{
	var $mData = false, $mMemcKey;

	function BlockCache( $deferLoad = false, $dbName = '' )
	{
		global $wgDBname;

		if ( $dbName == '' ) {
			$dbName = $wgDBname;
		}

		$this->mMemcKey = $dbName.':ipblocks';

		if ( !$deferLoad ) {
			$this->load();
		}
	}

	function load()
	{
		global $wgUseMemCached, $wgMemc;

		if ( $this->mData === false) {
			$saveMemc = false;
			# Try memcached
			if ( $wgUseMemCached ) {
				$this->mData = $wgMemc->get( $this->mMemcKey );
				if ( !$this->mData ) {
					$saveMemc = true;
				}
			}

			if ( !is_array( $this->mData ) ) {
				# Load from DB
				$this->mData = array();
				Block::enumBlocks( 'wfBlockCacheInsert', '' ); # Calls $this->insert()
			}
			
			if ( $saveMemc ) {
				$wgMemc->set( $this->mMemcKey, $this->mData, 0 );
			}
		}
	}

	function insert( &$block )
	{
		if ( $block->mUser == 0 ) {
			$nb = $block->getNetworkBits();
			$ipint = $block->getIntegerAddr();
			$index = $ipint >> ( 32 - $nb );

			if ( !array_key_exists( $nb, $this->mData ) ) {
				$this->mData[$nb] = array();
			}
		
			$this->mData[$nb][$index] = 1;
		}
	}
		
	function get( $ip )
	{
		$this->load();
		$ipint = ip2long( $ip );
		$blocked = false;

		foreach ( $this->mData as $networkBits => $blockInts ) {
			if ( array_key_exists( $ipint >> ( 32 - $networkBits ), $blockInts ) ) {
				$blocked = true;
				break;
			}
		}
		if ( $blocked ) {
			# Clear low order bits
			if ( $networkBits != 32 ) {
				$ip .= '/'.$networkBits;
				$ip = Block::normaliseRange( $ip );
			}
			$block = new Block();
			$block->load( $ip );
		} else {
			$block = false;
		}

		return $block;
	}

	function clear()
	{
		global $wgUseMemCached, $wgMemc;

		$this->mData = false;
		if ( $wgUseMemCached ) {
			$wgMemc->delete( $this->mMemcKey );
		}
	}

	function clearLocal()
	{
		$this->mData = false;
	}
}

function wfBlockCacheInsert( $block, $tag )
{
	global $wgBlockCache;
	$wgBlockCache->insert( $block );
}
