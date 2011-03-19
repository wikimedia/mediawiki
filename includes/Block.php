<?php
/**
 * @file
 * Blocks and bans object
 */

/**
 * The block class
 * All the functions in this class assume the object is either explicitly
 * loaded or filled. It is not load-on-demand. There are no accessors.
 *
 * Globals used: $wgAutoblockExpiry, $wgAntiLockFlags
 *
 * @todo This could be used everywhere, but it isn't.
 * FIXME: this whole class is a cesspit, needs a complete rewrite
 */
class Block {
	/* public*/ var $mAddress, $mUser, $mBy, $mReason, $mTimestamp, $mAuto, $mId, $mExpiry,
				$mRangeStart, $mRangeEnd, $mAnonOnly, $mEnableAutoblock, $mHideName,
				$mBlockEmail, $mByName, $mAngryAutoblock, $mAllowUsertalk;
	private $mFromMaster;

	const TYPE_USER = 1;
	const TYPE_IP = 2;
	const TYPE_RANGE = 3;
	const TYPE_AUTO = 4;
	const TYPE_ID = 5;

	function __construct( $address = '', $user = 0, $by = 0, $reason = '',
		$timestamp = 0, $auto = 0, $expiry = '', $anonOnly = 0, $createAccount = 0, $enableAutoblock = 0,
		$hideName = 0, $blockEmail = 0, $allowUsertalk = 0, $byName = false )
	{
		$this->mId = 0;
		# Expand valid IPv6 addresses
		$address = IP::sanitizeIP( $address );
		$this->mAddress = $address;
		$this->mUser = $user;
		$this->mBy = $by;
		$this->mReason = $reason;
		$this->mTimestamp = wfTimestamp( TS_MW, $timestamp );
		$this->mAuto = $auto;
		$this->mAnonOnly = $anonOnly;
		$this->mCreateAccount = $createAccount;
		$this->mExpiry = $expiry;
		$this->mEnableAutoblock = $enableAutoblock;
		$this->mHideName = $hideName;
		$this->mBlockEmail = $blockEmail;
		$this->mAllowUsertalk = $allowUsertalk;
		$this->mFromMaster = false;
		$this->mByName = $byName;
		$this->mAngryAutoblock = false;
		$this->initialiseRange();
	}

	/**
	 * Load a block from the database, using either the IP address or
	 * user ID. Tries the user ID first, and if that doesn't work, tries
	 * the address.
	 *
	 * @param $address String: IP address of user/anon
	 * @param $user Integer: user id of user
	 * @param $killExpired Boolean: delete expired blocks on load
	 * @return Block Object
	 */
	public static function newFromDB( $address, $user = 0, $killExpired = true ) {
		$block = new Block;
		$block->load( $address, $user, $killExpired );
		if ( $block->isValid() ) {
			return $block;
		} else {
			return null;
		}
	}

	/**
	 * Load a blocked user from their block id.
	 *
	 * @param $id Integer: Block id to search for
	 * @return Block object
	 */
	public static function newFromID( $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->resultObject( $dbr->select( 'ipblocks', '*',
			array( 'ipb_id' => $id ), __METHOD__ ) );
		$block = new Block;

		if ( $block->loadFromResult( $res ) ) {
			return $block;
		} else {
			return null;
		}
	}

	/**
	 * Check if two blocks are effectively equal
	 *
	 * @return Boolean
	 */
	public function equals( Block $block ) {
		return (
			$this->mAddress == $block->mAddress
			&& $this->mUser == $block->mUser
			&& $this->mAuto == $block->mAuto
			&& $this->mAnonOnly == $block->mAnonOnly
			&& $this->mCreateAccount == $block->mCreateAccount
			&& $this->mExpiry == $block->mExpiry
			&& $this->mEnableAutoblock == $block->mEnableAutoblock
			&& $this->mHideName == $block->mHideName
			&& $this->mBlockEmail == $block->mBlockEmail
			&& $this->mAllowUsertalk == $block->mAllowUsertalk
			&& $this->mReason == $block->mReason
		);
	}

	/**
	 * Clear all member variables in the current object. Does not clear
	 * the block from the DB.
	 */
	public function clear() {
		$this->mAddress = $this->mReason = $this->mTimestamp = '';
		$this->mId = $this->mAnonOnly = $this->mCreateAccount =
			$this->mEnableAutoblock = $this->mAuto = $this->mUser =
			$this->mBy = $this->mHideName = $this->mBlockEmail = $this->mAllowUsertalk = 0;
		$this->mByName = false;
	}

	/**
	 * Get a block from the DB, with either the given address or the given username
	 *
	 * @param $address string The IP address of the user, or blank to skip IP blocks
	 * @param $user int The user ID, or zero for anonymous users
	 * @param $killExpired bool Whether to delete expired rows while loading
	 * @return Boolean: the user is blocked from editing
	 *
	 */
	public function load( $address = '', $user = 0, $killExpired = true ) {
		wfDebug( "Block::load: '$address', '$user', $killExpired\n" );

		$db = wfGetDB( $this->mFromMaster ? DB_MASTER : DB_SLAVE );

		if ( 0 == $user && $address === '' ) {
			# Invalid user specification, not blocked
			$this->clear();

			return false;
		}

		# Try user block
		if ( $user ) {
			$res = $db->resultObject( $db->select(
				'ipblocks',
				'*',
				array( 'ipb_user' => $user ),
				__METHOD__
			) );

			if ( $this->loadFromResult( $res, $killExpired ) ) {
				return true;
			}
		}

		# Try IP block
		# TODO: improve performance by merging this query with the autoblock one
		# Slightly tricky while handling killExpired as well
		if ( $address !== '' ) {
			$conds = array( 'ipb_address' => $address, 'ipb_auto' => 0 );
			$res = $db->resultObject( $db->select(
				'ipblocks',
				'*',
				$conds,
				__METHOD__
			) );

			if ( $this->loadFromResult( $res, $killExpired ) ) {
				if ( $user && $this->mAnonOnly ) {
					# Block is marked anon-only
					# Whitelist this IP address against autoblocks and range blocks
					# (but not account creation blocks -- bug 13611)
					if ( !$this->mCreateAccount ) {
						$this->clear();
					}

					return false;
				} else {
					return true;
				}
			}
		}

		# Try range block
		if ( $this->loadRange( $address, $killExpired, $user ) ) {
			if ( $user && $this->mAnonOnly ) {
				# Respect account creation blocks on logged-in users -- bug 13611
				if ( !$this->mCreateAccount ) {
					$this->clear();
				}

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

			$res = $db->resultObject( $db->select(
				'ipblocks',
				'*',
				$conds,
				__METHOD__
			) );

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
	 *
	 * @param $res ResultWrapper: row from the ipblocks table
	 * @param $killExpired Boolean: whether to delete expired rows while loading
	 * @return Boolean
	 */
	protected function loadFromResult( ResultWrapper $res, $killExpired = true ) {
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
	 *
	 * @param $address String: IP address range
	 * @param $killExpired Boolean: whether to delete expired rows while loading
	 * @param $user Integer: if not 0, then sets ipb_anon_only
	 * @return Boolean
	 */
	public function loadRange( $address, $killExpired = true, $user = 0 ) {
		$iaddr = IP::toHex( $address );

		if ( $iaddr === false ) {
			# Invalid address
			return false;
		}

		# Only scan ranges which start in this /16, this improves search speed
		# Blocks should not cross a /16 boundary.
		$range = substr( $iaddr, 0, 4 );

		$db = wfGetDB( $this->mFromMaster ? DB_MASTER : DB_SLAVE );
		$conds = array(
			'ipb_range_start' . $db->buildLike( $range, $db->anyString() ),
			"ipb_range_start <= '$iaddr'",
			"ipb_range_end >= '$iaddr'"
		);

		if ( $user ) {
			$conds['ipb_anon_only'] = 0;
		}

		$res = $db->resultObject( $db->select( 'ipblocks', '*', $conds, __METHOD__ ) );
		$success = $this->loadFromResult( $res, $killExpired );

		return $success;
	}

	/**
	 * Given a database row from the ipblocks table, initialize
	 * member variables
	 *
	 * @param $row ResultWrapper: a row from the ipblocks table
	 */
	public function initFromRow( $row ) {
		$this->mAddress = $row->ipb_address;
		$this->mReason = $row->ipb_reason;
		$this->mTimestamp = wfTimestamp( TS_MW, $row->ipb_timestamp );
		$this->mUser = $row->ipb_user;
		$this->mBy = $row->ipb_by;
		$this->mAuto = $row->ipb_auto;
		$this->mAnonOnly = $row->ipb_anon_only;
		$this->mCreateAccount = $row->ipb_create_account;
		$this->mEnableAutoblock = $row->ipb_enable_autoblock;
		$this->mBlockEmail = $row->ipb_block_email;
		$this->mAllowUsertalk = $row->ipb_allow_usertalk;
		$this->mHideName = $row->ipb_deleted;
		$this->mId = $row->ipb_id;
		$this->mExpiry = $row->ipb_expiry;

		if ( isset( $row->user_name ) ) {
			$this->mByName = $row->user_name;
		} else {
			$this->mByName = $row->ipb_by_text;
		}

		$this->mRangeStart = $row->ipb_range_start;
		$this->mRangeEnd = $row->ipb_range_end;
	}

	/**
	 * Once $mAddress has been set, get the range they came from.
	 * Wrapper for IP::parseRange
	 */
	protected function initialiseRange() {
		$this->mRangeStart = '';
		$this->mRangeEnd = '';

		if ( $this->mUser == 0 ) {
			list( $this->mRangeStart, $this->mRangeEnd ) = IP::parseRange( $this->mAddress );
		}
	}

	/**
	 * Delete the row from the IP blocks table.
	 *
	 * @return Boolean
	 */
	public function delete() {
		if ( wfReadOnly() ) {
			return false;
		}

		if ( !$this->mId ) {
			throw new MWException( "Block::delete() now requires that the mId member be filled\n" );
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'ipblocks', array( 'ipb_id' => $this->mId ), __METHOD__ );

		return $dbw->affectedRows() > 0;
	}

	/**
	 * Insert a block into the block table. Will fail if there is a conflicting
	 * block (same name and options) already in the database.
	 *
	 * @return Boolean: whether or not the insertion was successful.
	 */
	public function insert( $dbw = null ) {
		wfDebug( "Block::insert; timestamp {$this->mTimestamp}\n" );

		if ( $dbw === null )
			$dbw = wfGetDB( DB_MASTER );

		$this->validateBlockParams();
		$this->initialiseRange();

		# Don't collide with expired blocks
		Block::purgeExpired();

		$ipb_id = $dbw->nextSequenceValue( 'ipblocks_ipb_id_seq' );
		$dbw->insert(
			'ipblocks',
			array(
				'ipb_id' => $ipb_id,
				'ipb_address' => $this->mAddress,
				'ipb_user' => $this->mUser,
				'ipb_by' => $this->mBy,
				'ipb_by_text' => $this->mByName,
				'ipb_reason' => $this->mReason,
				'ipb_timestamp' => $dbw->timestamp( $this->mTimestamp ),
				'ipb_auto' => $this->mAuto,
				'ipb_anon_only' => $this->mAnonOnly,
				'ipb_create_account' => $this->mCreateAccount,
				'ipb_enable_autoblock' => $this->mEnableAutoblock,
				'ipb_expiry' => $dbw->encodeExpiry( $this->mExpiry ),
				'ipb_range_start' => $this->mRangeStart,
				'ipb_range_end' => $this->mRangeEnd,
				'ipb_deleted'	=> intval( $this->mHideName ), // typecast required for SQLite
				'ipb_block_email' => $this->mBlockEmail,
				'ipb_allow_usertalk' => $this->mAllowUsertalk
			),
			'Block::insert',
			array( 'IGNORE' )
		);
		$affected = $dbw->affectedRows();

		if ( $affected )
			$this->doRetroactiveAutoblock();

		return (bool)$affected;
	}

	/**
	 * Update a block in the DB with new parameters.
	 * The ID field needs to be loaded first.
	 */
	public function update() {
		wfDebug( "Block::update; timestamp {$this->mTimestamp}\n" );
		$dbw = wfGetDB( DB_MASTER );

		$this->validateBlockParams();

		$dbw->update(
			'ipblocks',
			array(
				'ipb_user' => $this->mUser,
				'ipb_by' => $this->mBy,
				'ipb_by_text' => $this->mByName,
				'ipb_reason' => $this->mReason,
				'ipb_timestamp' => $dbw->timestamp( $this->mTimestamp ),
				'ipb_auto' => $this->mAuto,
				'ipb_anon_only' => $this->mAnonOnly,
				'ipb_create_account' => $this->mCreateAccount,
				'ipb_enable_autoblock' => $this->mEnableAutoblock,
				'ipb_expiry' => $dbw->encodeExpiry( $this->mExpiry ),
				'ipb_range_start' => $this->mRangeStart,
				'ipb_range_end' => $this->mRangeEnd,
				'ipb_deleted'	=> $this->mHideName,
				'ipb_block_email' => $this->mBlockEmail,
				'ipb_allow_usertalk' => $this->mAllowUsertalk
			),
			array( 'ipb_id' => $this->mId ),
			'Block::update'
		);

		return $dbw->affectedRows();
	}

	/**
	 * Make sure all the proper members are set to sane values
	 * before adding/updating a block
	 */
	protected function validateBlockParams() {
		# Unset ipb_anon_only for user blocks, makes no sense
		if ( $this->mUser ) {
			$this->mAnonOnly = 0;
		}

		# Unset ipb_enable_autoblock for IP blocks, makes no sense
		if ( !$this->mUser ) {
			$this->mEnableAutoblock = 0;
		}

		# bug 18860: non-anon-only IP blocks should be allowed to block email
		if ( !$this->mUser && $this->mAnonOnly ) {
			$this->mBlockEmail = 0;
		}

		if ( !$this->mByName ) {
			if ( $this->mBy ) {
				$this->mByName = User::whoIs( $this->mBy );
			} else {
				global $wgUser;
				$this->mByName = $wgUser->getName();
			}
		}
	}

	/**
	 * Retroactively autoblocks the last IP used by the user (if it is a user)
	 * blocked by this Block.
	 *
	 * @return Boolean: whether or not a retroactive autoblock was made.
	 */
	public function doRetroactiveAutoblock() {
		$dbr = wfGetDB( DB_SLAVE );
		# If autoblock is enabled, autoblock the LAST IP used
		# - stolen shamelessly from CheckUser_body.php

		if ( $this->mEnableAutoblock && $this->mUser ) {
			wfDebug( "Doing retroactive autoblocks for " . $this->mAddress . "\n" );

			$options = array( 'ORDER BY' => 'rc_timestamp DESC' );
			$conds = array( 'rc_user_text' => $this->mAddress );

			if ( $this->mAngryAutoblock ) {
				// Block any IP used in the last 7 days. Up to five IPs.
				$conds[] = 'rc_timestamp < ' . $dbr->addQuotes( $dbr->timestamp( time() - ( 7 * 86400 ) ) );
				$options['LIMIT'] = 5;
			} else {
				// Just the last IP used.
				$options['LIMIT'] = 1;
			}

			$res = $dbr->select( 'recentchanges', array( 'rc_ip' ), $conds,
				__METHOD__ ,  $options );

			if ( !$dbr->numRows( $res ) ) {
				# No results, don't autoblock anything
				wfDebug( "No IP found to retroactively autoblock\n" );
			} else {
				foreach ( $res as $row ) {
					if ( $row->rc_ip ) {
						$this->doAutoblock( $row->rc_ip );
					}
				}
			}
		}
	}

	/**
	 * Checks whether a given IP is on the autoblock whitelist.
	 *
	 * @param $ip String: The IP to check
	 * @return Boolean
	 */
	public static function isWhitelistedFromAutoblocks( $ip ) {
		global $wgMemc;

		// Try to get the autoblock_whitelist from the cache, as it's faster
		// than getting the msg raw and explode()'ing it.
		$key = wfMemcKey( 'ipb', 'autoblock', 'whitelist' );
		$lines = $wgMemc->get( $key );
		if ( !$lines ) {
			$lines = explode( "\n", wfMsgForContentNoTrans( 'autoblock_whitelist' ) );
			$wgMemc->set( $key, $lines, 3600 * 24 );
		}

		wfDebug( "Checking the autoblock whitelist..\n" );

		foreach ( $lines as $line ) {
			# List items only
			if ( substr( $line, 0, 1 ) !== '*' ) {
				continue;
			}

			$wlEntry = substr( $line, 1 );
			$wlEntry = trim( $wlEntry );

			wfDebug( "Checking $ip against $wlEntry..." );

			# Is the IP in this range?
			if ( IP::isInRange( $ip, $wlEntry ) ) {
				wfDebug( " IP $ip matches $wlEntry, not autoblocking\n" );
				return true;
			} else {
				wfDebug( " No match\n" );
			}
		}

		return false;
	}

	/**
	 * Autoblocks the given IP, referring to this Block.
	 *
	 * @param $autoblockIP String: the IP to autoblock.
	 * @param $justInserted Boolean: the main block was just inserted
	 * @return Boolean: whether or not an autoblock was inserted.
	 */
	public function doAutoblock( $autoblockIP, $justInserted = false ) {
		# If autoblocks are disabled, go away.
		if ( !$this->mEnableAutoblock ) {
			return;
		}

		# Check for presence on the autoblock whitelist
		if ( Block::isWhitelistedFromAutoblocks( $autoblockIP ) ) {
			return;
		}

		# # Allow hooks to cancel the autoblock.
		if ( !wfRunHooks( 'AbortAutoblock', array( $autoblockIP, &$this ) ) ) {
			wfDebug( "Autoblock aborted by hook.\n" );
			return false;
		}

		# It's okay to autoblock. Go ahead and create/insert the block.

		$ipblock = Block::newFromDB( $autoblockIP );
		if ( $ipblock ) {
			# If the user is already blocked. Then check if the autoblock would
			# exceed the user block. If it would exceed, then do nothing, else
			# prolong block time
			if ( $this->mExpiry &&
				( $this->mExpiry < Block::getAutoblockExpiry( $ipblock->mTimestamp ) )
			) {
				return;
			}

			# Just update the timestamp
			if ( !$justInserted ) {
				$ipblock->updateTimestamp();
			}

			return;
		} else {
			$ipblock = new Block;
		}

		# Make a new block object with the desired properties
		wfDebug( "Autoblocking {$this->mAddress}@" . $autoblockIP . "\n" );
		$ipblock->mAddress = $autoblockIP;
		$ipblock->mUser = 0;
		$ipblock->mBy = $this->mBy;
		$ipblock->mByName = $this->mByName;
		$ipblock->mReason = wfMsgForContent( 'autoblocker', $this->mAddress, $this->mReason );
		$ipblock->mTimestamp = wfTimestampNow();
		$ipblock->mAuto = 1;
		$ipblock->mCreateAccount = $this->mCreateAccount;
		# Continue suppressing the name if needed
		$ipblock->mHideName = $this->mHideName;
		$ipblock->mAllowUsertalk = $this->mAllowUsertalk;

		# If the user is already blocked with an expiry date, we don't
		# want to pile on top of that!
		if ( $this->mExpiry ) {
			$ipblock->mExpiry = min( $this->mExpiry, Block::getAutoblockExpiry( $this->mTimestamp ) );
		} else {
			$ipblock->mExpiry = Block::getAutoblockExpiry( $this->mTimestamp );
		}

		# Insert it
		return $ipblock->insert();
	}

	/**
	 * Check if a block has expired. Delete it if it is.
	 * @return Boolean
	 */
	public function deleteIfExpired() {
		wfProfileIn( __METHOD__ );

		if ( $this->isExpired() ) {
			wfDebug( "Block::deleteIfExpired() -- deleting\n" );
			$this->delete();
			$retVal = true;
		} else {
			wfDebug( "Block::deleteIfExpired() -- not expired\n" );
			$retVal = false;
		}

		wfProfileOut( __METHOD__ );
		return $retVal;
	}

	/**
	 * Has the block expired?
	 * @return Boolean
	 */
	public function isExpired() {
		wfDebug( "Block::isExpired() checking current " . wfTimestampNow() . " vs $this->mExpiry\n" );

		if ( !$this->mExpiry ) {
			return false;
		} else {
			return wfTimestampNow() > $this->mExpiry;
		}
	}

	/**
	 * Is the block address valid (i.e. not a null string?)
	 * @return Boolean
	 */
	public function isValid() {
		return $this->mAddress != '';
	}

	/**
	 * Update the timestamp on autoblocks.
	 */
	public function updateTimestamp() {
		if ( $this->mAuto ) {
			$this->mTimestamp = wfTimestamp();
			$this->mExpiry = Block::getAutoblockExpiry( $this->mTimestamp );

			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'ipblocks',
				array( /* SET */
					'ipb_timestamp' => $dbw->timestamp( $this->mTimestamp ),
					'ipb_expiry' => $dbw->timestamp( $this->mExpiry ),
				), array( /* WHERE */
					'ipb_address' => $this->mAddress
				), 'Block::updateTimestamp'
			);
		}
	}

	/**
	 * Get the user id of the blocking sysop
	 *
	 * @return Integer
	 */
	public function getBy() {
		return $this->mBy;
	}

	/**
	 * Get the username of the blocking sysop
	 *
	 * @return String
	 */
	public function getByName() {
		return $this->mByName;
	}

	/**
	 * Get/set the SELECT ... FOR UPDATE flag
	 * @deprecated since 1.18
	 */
	public function forUpdate( $x = null ) {
		# noop
	}

	/**
	 * Get/set a flag determining whether the master is used for reads
	 */
	public function fromMaster( $x = null ) {
		return wfSetVar( $this->mFromMaster, $x );
	}

	/**
	 * Get the block name, but with autoblocked IPs hidden as per standard privacy policy
	 * @return String, text is escaped
	 */
	public function getRedactedName() {
		if ( $this->mAuto ) {
			return HTML::rawElement(
				'span',
				array( 'class' => 'mw-autoblockid' ),
				wfMessage( 'autoblockid', $this->mId )
			);
		} else {
			return htmlspecialchars( $this->mAddress );
		}
	}

	/**
	 * Encode expiry for DB
	 *
	 * @param $expiry String: timestamp for expiry, or
	 * @param $db Database object
	 * @return String
	 * @deprecated since 1.18; use $dbw->encodeExpiry() instead
	 */
	public static function encodeExpiry( $expiry, $db ) {
		return $db->encodeExpiry( $expiry );
	}

	/**
	 * Decode expiry which has come from the DB
	 *
	 * @param $expiry String: Database expiry format
	 * @param $timestampType Requested timestamp format
	 * @return String
	 * @deprecated since 1.18; use $wgLang->decodeExpiry() instead
	 */
	public static function decodeExpiry( $expiry, $timestampType = TS_MW ) {
		global $wgContLang;
		return $wgContLang->formatExpiry( $expiry, $timestampType );
	}

	/**
	 * Get a timestamp of the expiry for autoblocks
	 *
	 * @return String
	 */
	public static function getAutoblockExpiry( $timestamp ) {
		global $wgAutoblockExpiry;

		return wfTimestamp( TS_MW, wfTimestamp( TS_UNIX, $timestamp ) + $wgAutoblockExpiry );
	}

	/**
	 * Gets rid of uneeded numbers in quad-dotted/octet IP strings
	 * For example, 127.111.113.151/24 -> 127.111.113.0/24
	 * @param $range String: IP address to normalize
	 * @return string
	 * @deprecated since 1.18, call IP::sanitizeRange() directly
	 */
	public static function normaliseRange( $range ) {
		return IP::sanitizeRange( $range );
	}

	/**
	 * Purge expired blocks from the ipblocks table
	 */
	public static function purgeExpired() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'ipblocks', array( 'ipb_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ), __METHOD__ );
	}

	/**
	 * Get a value to insert into expiry field of the database when infinite expiry
	 * is desired
	 * @deprecated since 1.18, call $dbr->getInfinity() directly
	 * @return String
	 */
	public static function infinity() {
		return wfGetDB( DB_SLAVE )->getInfinity();
	}

	/**
	 * Convert a DB-encoded expiry into a real string that humans can read.
	 *
	 * @param $encoded_expiry String: Database encoded expiry time
	 * @return Html-escaped String
	 * @deprecated since 1.18; use $wgLang->formatExpiry() instead
	 */
	public static function formatExpiry( $encoded_expiry ) {
		global $wgContLang;
		static $msg = null;

		if ( is_null( $msg ) ) {
			$msg = array();
			$keys = array( 'infiniteblock', 'expiringblock' );

			foreach ( $keys as $key ) {
				$msg[$key] = wfMsgHtml( $key );
			}
		}

		$expiry = $wgContLang->formatExpiry( $encoded_expiry, TS_MW );
		if ( $expiry == wfGetDB( DB_SLAVE )->getInfinity() ) {
			$expirystr = $msg['infiniteblock'];
		} else {
			global $wgLang;
			$expiredatestr = htmlspecialchars( $wgLang->date( $expiry, true ) );
			$expiretimestr = htmlspecialchars( $wgLang->time( $expiry, true ) );
			$expirystr = wfMsgReplaceArgs( $msg['expiringblock'], array( $expiredatestr, $expiretimestr ) );
		}

		return $expirystr;
	}

	# FIXME: everything above here is a mess, needs much cleaning up

	/**
	 * Convert a submitted expiry time, which may be relative ("2 weeks", etc) or absolute
	 * ("24 May 2034"), into an absolute timestamp we can put into the database.
	 * @param $expiry String: whatever was typed into the form
	 * @return String: timestamp or "infinity" string for th DB implementation
	 * @deprecated since 1.18 moved to SpecialBlock::parseExpiryInput()
	 */
	public static function parseExpiryInput( $expiry ) {
		wfDeprecated( __METHOD__ );
		return SpecialBlock::parseExpiryInput( $expiry );
	}

	/**
	 * Given a target and the target's type, get an existing Block object if possible.
	 * Note that passing an IP address will get an applicable rangeblock if the IP is
	 * not individually blocked but falls within that range
	 * TODO: check that that fallback handles nested rangeblocks nicely (should return
	 *     smallest one)
	 * @param $target String|User|Int a block target, which may be one of several types:
	 *     * A user to block, in which case $target will be a User
	 *     * An IP to block, in which case $target will be a User generated by using
	 *       User::newFromName( $ip, false ) to turn off name validation
	 *     * An IP range, in which case $target will be a String "123.123.123.123/18" etc
	 *     * The ID of an existing block, in which case $target will be an Int
	 * @param $type Block::TYPE_ constant the type of block as described above
	 * @return Block|null (null if the target is not blocked)
	 */
	public static function newFromTargetAndType( $target, $type ){
		if( $target instanceof User ){
			if( $type == Block::TYPE_IP ){
				return Block::newFromDB( $target->getName(), 0 );
			} elseif( $type == Block::TYPE_USER ) {
				return Block::newFromDB( '', $target->getId() );
			} else {
				# Should be unreachable;
				return null;
			}

		} elseif( $type == Block::TYPE_RANGE ){
			return Block::newFromDB( $target, 0 );

		} elseif( $type == Block::TYPE_ID || $type == Block::TYPE_AUTO ){
			return Block::newFromID( $target );

		} else {
			return null;
		}
	}

	public static function newFromTarget( $target ){
		list( $target, $type ) = self::parseTarget( $target );
		return self::newFromTargetAndType( $target, $type );
	}

	/**
	 * From an existing Block, get the target and the type of target.  Note that it is
	 * always safe to treat the target as a string; for User objects this will return
	 * User::__toString() which in turn gives User::getName().
	 * @return array( User|String, Block::TYPE_ constant )
	 */
	public static function parseTarget( $target ){
		$target = trim( $target );

		$userObj = User::newFromName( $target );
		if( $userObj instanceof User ){
			# Note that since numbers are valid usernames, a $target of "12345" will be
			# considered a User.  If you want to pass a block ID, prepend a hash "#12345",
			# since hash characters are not valid in usernames or titles generally.
			return array( $userObj, Block::TYPE_USER );

		} elseif( IP::isValid( $target ) ){
			# We can still create a User if it's an IP address, but we need to turn
			# off validation checking (which would exclude IP addresses)
			return array(
				User::newFromName( IP::sanitizeIP( $target ), false ),
				Block::TYPE_IP
			);

		} elseif( IP::isValidBlock( $target ) ){
			# Can't create a User from an IP range
			return array( Block::normaliseRange( $target ), Block::TYPE_RANGE );

		} elseif( preg_match( '/^#\d+$/', $target ) ){
			# Autoblock reference in the form "#12345"
			return array( substr( $target, 1 ), Block::TYPE_AUTO );

		} else {
			# WTF?
			return array( null, null );
		}
	}

	/**
	 * Get the target and target type for this particular Block.  Note that for autoblocks,
	 * this returns the unredacted name; frontend functions need to call $block->getRedactedName()
	 * in this situation.
	 * @return array( User|String, Block::TYPE_ constant )
	 * FIXME: this should be an integral part of the Block member variables
	 */
	public function getTargetAndType(){
		list( $target, $type ) = self::parseTarget( $this->mAddress );

		# Check whether it's an autoblock
		if( $this->mAuto ){
			$type = self::TYPE_AUTO;
		}

		return array( $target, $type );
	}

	public function getType(){
		list( /*...*/, $type ) = $this->getTargetAndType();
		return $type;
	}

	public function getTarget(){
		list( $target, /*...*/ ) = $this->getTargetAndType();
		return $target;
	}
}
