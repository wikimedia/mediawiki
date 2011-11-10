<?php
/**
 * Blocks and bans object
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */
class Block {
	/* public*/ var $mReason, $mTimestamp, $mAuto, $mExpiry, $mHideName;

	protected
		$mId,
		$mFromMaster,

		$mBlockEmail,
		$mDisableUsertalk,
		$mCreateAccount;

	/// @var User|String
	protected $target;

	/// @var Block::TYPE_ constant.  Can only be USER, IP or RANGE internally
	protected $type;

	/// @var User
	protected $blocker;

	/// @var Bool
	protected $isHardblock = true;

	/// @var Bool
	protected $isAutoblocking = true;

	# TYPE constants
	const TYPE_USER = 1;
	const TYPE_IP = 2;
	const TYPE_RANGE = 3;
	const TYPE_AUTO = 4;
	const TYPE_ID = 5;

	/**
	 * Constructor
	 * @todo FIXME: Don't know what the best format to have for this constructor is, but fourteen
	 * optional parameters certainly isn't it.
	 */
	function __construct( $address = '', $user = 0, $by = 0, $reason = '',
		$timestamp = 0, $auto = 0, $expiry = '', $anonOnly = 0, $createAccount = 0, $enableAutoblock = 0,
		$hideName = 0, $blockEmail = 0, $allowUsertalk = 0, $byText = '' )
	{
		if( $timestamp === 0 ){
			$timestamp = wfTimestampNow();
		}

		if( count( func_get_args() ) > 0 ){
			# Soon... :D
			# wfDeprecated( __METHOD__ . " with arguments" );
		}

		$this->setTarget( $address );
		if ( $by ) { // local user
			$this->setBlocker( User::newFromID( $by ) );
		} else { // foreign user
			$this->setBlocker( $byText );
		}
		$this->mReason = $reason;
		$this->mTimestamp = wfTimestamp( TS_MW, $timestamp );
		$this->mAuto = $auto;
		$this->isHardblock( !$anonOnly );
		$this->prevents( 'createaccount', $createAccount );
		if ( $expiry == wfGetDB( DB_SLAVE )->getInfinity() ) {
			$this->mExpiry = $expiry;
		} else {
			$this->mExpiry = wfTimestamp( TS_MW, $expiry );
		}
		$this->isAutoblocking( $enableAutoblock );
		$this->mHideName = $hideName;
		$this->prevents( 'sendemail', $blockEmail );
		$this->prevents( 'editownusertalk', !$allowUsertalk );

		$this->mFromMaster = false;
	}

	/**
	 * Load a block from the database, using either the IP address or
	 * user ID. Tries the user ID first, and if that doesn't work, tries
	 * the address.
	 *
	 * @param $address String: IP address of user/anon
	 * @param $user Integer: user id of user
	 * @return Block Object
	 * @deprecated since 1.18
	 */
	public static function newFromDB( $address, $user = 0 ) {
		return self::newFromTarget( User::whoIs( $user ), $address );
	}

	/**
	 * Load a blocked user from their block id.
	 *
	 * @param $id Integer: Block id to search for
	 * @return Block object or null
	 */
	public static function newFromID( $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow(
			'ipblocks',
			'*',
			array( 'ipb_id' => $id ),
			__METHOD__
		);
		if ( $res ) {
			return Block::newFromRow( $res );
		} else {
			return null;
		}
	}

	/**
	 * Check if two blocks are effectively equal.  Doesn't check irrelevant things like
	 * the blocking user or the block timestamp, only things which affect the blocked user	 *
	 *
	 * @param $block Block
	 *
	 * @return bool
	 */
	public function equals( Block $block ) {
		return (
			(string)$this->target == (string)$block->target
			&& $this->type == $block->type
			&& $this->mAuto == $block->mAuto
			&& $this->isHardblock() == $block->isHardblock()
			&& $this->prevents( 'createaccount' ) == $block->prevents( 'createaccount' )
			&& $this->mExpiry == $block->mExpiry
			&& $this->isAutoblocking() == $block->isAutoblocking()
			&& $this->mHideName == $block->mHideName
			&& $this->prevents( 'sendemail' ) == $block->prevents( 'sendemail' )
			&& $this->prevents( 'editownusertalk' ) == $block->prevents( 'editownusertalk' )
			&& $this->mReason == $block->mReason
		);
	}

	/**
	 * Clear all member variables in the current object. Does not clear
	 * the block from the DB.
	 * @deprecated since 1.18
	 */
	public function clear() {
		# Noop
	}

	/**
	 * Get a block from the DB, with either the given address or the given username
	 *
	 * @param $address string The IP address of the user, or blank to skip IP blocks
	 * @param $user int The user ID, or zero for anonymous users
	 * @return Boolean: the user is blocked from editing
	 * @deprecated since 1.18
	 */
	public function load( $address = '', $user = 0 ) {
		wfDeprecated( __METHOD__ );
		if( $user ){
			$username = User::whoIs( $user );
			$block = self::newFromTarget( $username, $address );
		} else {
			$block = self::newFromTarget( null, $address );
		}

		if( $block instanceof Block ){
			# This is mildly evil, but hey, it's B/C :D
			foreach( $block as $variable => $value ){
				$this->$variable = $value;
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Load a block from the database which affects the already-set $this->target:
	 *     1) A block directly on the given user or IP
	 *     2) A rangeblock encompasing the given IP (smallest first)
	 *     3) An autoblock on the given IP
	 * @param $vagueTarget User|String also search for blocks affecting this target.  Doesn't
	 *     make any sense to use TYPE_AUTO / TYPE_ID here. Leave blank to skip IP lookups.
	 * @return Bool whether a relevant block was found
	 */
	protected function newLoad( $vagueTarget = null ) {
		$db = wfGetDB( $this->mFromMaster ? DB_MASTER : DB_SLAVE );

		if( $this->type !== null ){
			$conds = array(
				'ipb_address' => array( (string)$this->target ),
			);
		} else {
			$conds = array( 'ipb_address' => array() );
		}

		# Be aware that the != '' check is explicit, since empty values will be
		# passed by some callers (bug 29116)
		if( $vagueTarget != ''){
			list( $target, $type ) = self::parseTarget( $vagueTarget );
			switch( $type ) {
				case self::TYPE_USER:
					# Slightly wierd, but who are we to argue?
					$conds['ipb_address'][] = (string)$target;
					break;

				case self::TYPE_IP:
					$conds['ipb_address'][] = (string)$target;
					$conds[] = self::getRangeCond( IP::toHex( $target ) );
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				case self::TYPE_RANGE:
					list( $start, $end ) = IP::parseRange( $target );
					$conds['ipb_address'][] = (string)$target;
					$conds[] = self::getRangeCond( $start, $end );
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				default:
					throw new MWException( "Tried to load block with invalid type" );
			}
		}

		$res = $db->select( 'ipblocks', '*', $conds, __METHOD__ );

		# This result could contain a block on the user, a block on the IP, and a russian-doll
		# set of rangeblocks.  We want to choose the most specific one, so keep a leader board.
		$bestRow = null;

		# Lower will be better
		$bestBlockScore = 100;

		# This is begging for $this = $bestBlock, but that's not allowed in PHP :(
		$bestBlockPreventsEdit = null;

		foreach( $res as $row ){
			$block = Block::newFromRow( $row );

			# Don't use expired blocks
			if( $block->deleteIfExpired() ){
				continue;
			}

			# Don't use anon only blocks on users
			if( $this->type == self::TYPE_USER && !$block->isHardblock() ){
				continue;
			}

			if( $block->getType() == self::TYPE_RANGE ){
				# This is the number of bits that are allowed to vary in the block, give
				# or take some floating point errors
				$end = wfBaseconvert( $block->getRangeEnd(), 16, 10 );
				$start = wfBaseconvert( $block->getRangeStart(), 16, 10 );
				$size = log( $end - $start + 1, 2 );

				# This has the nice property that a /32 block is ranked equally with a
				# single-IP block, which is exactly what it is...
				$score = self::TYPE_RANGE  - 1 + ( $size / 128 );

			} else {
				$score = $block->getType();
			}

			if( $score < $bestBlockScore ){
				$bestBlockScore = $score;
				$bestRow = $row;
				$bestBlockPreventsEdit = $block->prevents( 'edit' );
			}
		}

		if( $bestRow !== null ){
			$this->initFromRow( $bestRow );
			$this->prevents( 'edit', $bestBlockPreventsEdit );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get a set of SQL conditions which will select rangeblocks encompasing a given range
	 * @param $start String Hexadecimal IP representation
	 * @param $end String Hexadecimal IP represenation, or null to use $start = $end
	 * @return String
	 */
	public static function getRangeCond( $start, $end = null ) {
		if ( $end === null ) {
			$end = $start;
		}
		# Per bug 14634, we want to include relevant active rangeblocks; for
		# rangeblocks, we want to include larger ranges which enclose the given
		# range. We know that all blocks must be smaller than $wgBlockCIDRLimit,
		# so we can improve performance by filtering on a LIKE clause
		$chunk = self::getIpFragment( $start );
		$dbr = wfGetDB( DB_SLAVE );
		$like = $dbr->buildLike( $chunk, $dbr->anyString() );

		# Fairly hard to make a malicious SQL statement out of hex characters,
		# but stranger things have happened...
		$safeStart = $dbr->addQuotes( $start );
		$safeEnd = $dbr->addQuotes( $end );

		return $dbr->makeList(
			array(
				"ipb_range_start $like",
				"ipb_range_start <= $safeStart",
				"ipb_range_end >= $safeEnd",
			),
			LIST_AND
		);
	}

	/**
	 * Get the component of an IP address which is certain to be the same between an IP
	 * address and a rangeblock containing that IP address.
	 * @param  $hex String Hexadecimal IP representation
	 * @return String
	 */
	protected static function getIpFragment( $hex ) {
		global $wgBlockCIDRLimit;
		if ( substr( $hex, 0, 3 ) == 'v6-' ) {
			return 'v6-' . substr( substr( $hex, 3 ), 0,  floor( $wgBlockCIDRLimit['IPv6'] / 4 ) );
		} else {
			return substr( $hex, 0,  floor( $wgBlockCIDRLimit['IPv4'] / 4 ) );
		}
	}

	/**
	 * Given a database row from the ipblocks table, initialize
	 * member variables
	 * @param $row ResultWrapper: a row from the ipblocks table
	 */
	protected function initFromRow( $row ) {
		$this->setTarget( $row->ipb_address );
		if ( $row->ipb_by ) { // local user
			$this->setBlocker( User::newFromID( $row->ipb_by ) );
		} else { // foreign user
			$this->setBlocker( $row->ipb_by_text );
		}

		$this->mReason = $row->ipb_reason;
		$this->mTimestamp = wfTimestamp( TS_MW, $row->ipb_timestamp );
		$this->mAuto = $row->ipb_auto;
		$this->mHideName = $row->ipb_deleted;
		$this->mId = $row->ipb_id;

		// I wish I didn't have to do this
		if ( $row->ipb_expiry == wfGetDB( DB_SLAVE )->getInfinity() ) {
			$this->mExpiry = $row->ipb_expiry;
		} else {
			$this->mExpiry = wfTimestamp( TS_MW, $row->ipb_expiry );
		}

		$this->isHardblock( !$row->ipb_anon_only );
		$this->isAutoblocking( $row->ipb_enable_autoblock );

		$this->prevents( 'createaccount', $row->ipb_create_account );
		$this->prevents( 'sendemail', $row->ipb_block_email );
		$this->prevents( 'editownusertalk', !$row->ipb_allow_usertalk );
	}

	/**
	 * Create a new Block object from a database row
	 * @param  $row ResultWrapper row from the ipblocks table
	 * @return Block
	 */
	public static function newFromRow( $row ){
		$block = new Block;
		$block->initFromRow( $row );
		return $block;
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

		if ( !$this->getId() ) {
			throw new MWException( "Block::delete() requires that the mId member be filled\n" );
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'ipblocks', array( 'ipb_id' => $this->getId() ), __METHOD__ );

		return $dbw->affectedRows() > 0;
	}

	/**
	 * Insert a block into the block table. Will fail if there is a conflicting
	 * block (same name and options) already in the database.
	 *
	 * @param $dbw DatabaseBase if you have one available
	 * @return mixed: false on failure, assoc array on success:
	 *	('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function insert( $dbw = null ) {
		wfDebug( "Block::insert; timestamp {$this->mTimestamp}\n" );

		if ( $dbw === null ) {
			$dbw = wfGetDB( DB_MASTER );
		}

		# Don't collide with expired blocks
		Block::purgeExpired();

		$row = $this->getDatabaseArray();
		$row['ipb_id'] = $dbw->nextSequenceValue("ipblocks_ipb_id_seq");
		
		$dbw->insert(
			'ipblocks',
			$row,
			__METHOD__,
			array( 'IGNORE' )
		);
		$affected = $dbw->affectedRows();
		$this->mId = $dbw->insertId();

		if ( $affected ) {
			$auto_ipd_ids = $this->doRetroactiveAutoblock();
			return array( 'id' => $this->mId, 'autoIds' => $auto_ipd_ids );
		}

		return false;
	}

	/**
	 * Update a block in the DB with new parameters.
	 * The ID field needs to be loaded first.
	 *
	 * @return Int number of affected rows, which should probably be 1 or something's
	 *     gone slightly awry
	 */
	public function update() {
		wfDebug( "Block::update; timestamp {$this->mTimestamp}\n" );
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update(
			'ipblocks',
			$this->getDatabaseArray( $dbw ),
			array( 'ipb_id' => $this->getId() ),
			__METHOD__
		);

		return $dbw->affectedRows();
	}

	/**
	 * Get an array suitable for passing to $dbw->insert() or $dbw->update()
	 * @param $db DatabaseBase
	 * @return Array
	 */
	protected function getDatabaseArray( $db = null ){
		if( !$db ){
			$db = wfGetDB( DB_SLAVE );
		}
		$expiry = $db->encodeExpiry( $this->mExpiry );

		$a = array(
			'ipb_address'          => (string)$this->target,
			'ipb_user'             => $this->target instanceof User ? $this->target->getID() : 0,
			'ipb_by'               => $this->getBy(),
			'ipb_by_text'          => $this->getByName(),
			'ipb_reason'           => $this->mReason,
			'ipb_timestamp'        => $db->timestamp( $this->mTimestamp ),
			'ipb_auto'             => $this->mAuto,
			'ipb_anon_only'        => !$this->isHardblock(),
			'ipb_create_account'   => $this->prevents( 'createaccount' ),
			'ipb_enable_autoblock' => $this->isAutoblocking(),
			'ipb_expiry'           => $expiry,
			'ipb_range_start'      => $this->getRangeStart(),
			'ipb_range_end'        => $this->getRangeEnd(),
			'ipb_deleted'	       => intval( $this->mHideName ), // typecast required for SQLite
			'ipb_block_email'      => $this->prevents( 'sendemail' ),
			'ipb_allow_usertalk'   => !$this->prevents( 'editownusertalk' )
		);

		return $a;
	}

	/**
	 * Retroactively autoblocks the last IP used by the user (if it is a user)
	 * blocked by this Block.
	 *
	 * @return Array: block IDs of retroactive autoblocks made
	 */
	protected function doRetroactiveAutoblock() {
		$blockIds = array();
		# If autoblock is enabled, autoblock the LAST IP(s) used
		if ( $this->isAutoblocking() && $this->getType() == self::TYPE_USER ) {
			wfDebug( "Doing retroactive autoblocks for " . $this->getTarget() . "\n" );

			$continue = wfRunHooks(
				'PerformRetroactiveAutoblock', array( $this, &$blockIds ) );

			if ( $continue ) {
				self::defaultRetroactiveAutoblock( $this, $blockIds );
			}
		}
		return $blockIds;
	}

	/**
	 * Retroactively autoblocks the last IP used by the user (if it is a user)
	 * blocked by this Block. This will use the recentchanges table.
	 *
	 * @param Block $block
	 * @param Array &$blockIds
	 * @return Array: block IDs of retroactive autoblocks made
	 */
	protected static function defaultRetroactiveAutoblock( Block $block, array &$blockIds ) {
		$dbr = wfGetDB( DB_SLAVE );

		$options = array( 'ORDER BY' => 'rc_timestamp DESC' );
		$conds = array( 'rc_user_text' => (string)$block->getTarget() );

		// Just the last IP used.
		$options['LIMIT'] = 1;

		$res = $dbr->select( 'recentchanges', array( 'rc_ip' ), $conds,
			__METHOD__ ,  $options );

		if ( !$dbr->numRows( $res ) ) {
			# No results, don't autoblock anything
			wfDebug( "No IP found to retroactively autoblock\n" );
		} else {
			foreach ( $res as $row ) {
				if ( $row->rc_ip ) {
					$id = $block->doAutoblock( $row->rc_ip );
					if ( $id ) $blockIds[] = $id;
				}
			}
		}
	}

	/**
	 * Checks whether a given IP is on the autoblock whitelist.
	 * TODO: this probably belongs somewhere else, but not sure where...
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
	 * @return mixed: block ID if an autoblock was inserted, false if not.
	 */
	public function doAutoblock( $autoblockIP ) {
		# If autoblocks are disabled, go away.
		if ( !$this->isAutoblocking() ) {
			return false;
		}

		# Check for presence on the autoblock whitelist.
		if ( self::isWhitelistedFromAutoblocks( $autoblockIP ) ) {
			return false;
		}

		# Allow hooks to cancel the autoblock.
		if ( !wfRunHooks( 'AbortAutoblock', array( $autoblockIP, &$this ) ) ) {
			wfDebug( "Autoblock aborted by hook.\n" );
			return false;
		}

		# It's okay to autoblock. Go ahead and insert/update the block...

		# Do not add a *new* block if the IP is already blocked.
		$ipblock = Block::newFromTarget( $autoblockIP );
		if ( $ipblock ) {
			# Check if the block is an autoblock and would exceed the user block
			# if renewed. If so, do nothing, otherwise prolong the block time...
			if ( $ipblock->mAuto && // @TODO: why not compare $ipblock->mExpiry?
				$this->mExpiry > Block::getAutoblockExpiry( $ipblock->mTimestamp )
			) {
				# Reset block timestamp to now and its expiry to
				# $wgAutoblockExpiry in the future
				$ipblock->updateTimestamp();
			}
			return false;
		}

		# Make a new block object with the desired properties.
		$autoblock = new Block;
		wfDebug( "Autoblocking {$this->getTarget()}@" . $autoblockIP . "\n" );
		$autoblock->setTarget( $autoblockIP );
		$autoblock->setBlocker( $this->getBlocker() );
		$autoblock->mReason = wfMsgForContent( 'autoblocker', $this->getTarget(), $this->mReason );
		$timestamp = wfTimestampNow();
		$autoblock->mTimestamp = $timestamp;
		$autoblock->mAuto = 1;
		$autoblock->prevents( 'createaccount', $this->prevents( 'createaccount' ) );
		# Continue suppressing the name if needed
		$autoblock->mHideName = $this->mHideName;
		$autoblock->prevents( 'editownusertalk', $this->prevents( 'editownusertalk' ) );

		if ( $this->mExpiry == wfGetDB( DB_SLAVE )->getInfinity() ) {
			# Original block was indefinite, start an autoblock now
			$autoblock->mExpiry = Block::getAutoblockExpiry( $timestamp );
		} else {
			# If the user is already blocked with an expiry date, we don't
			# want to pile on top of that.
			$autoblock->mExpiry = min( $this->mExpiry, Block::getAutoblockExpiry( $timestamp ) );
		}

		# Insert the block...
		$status = $autoblock->insert();
		return $status
			? $status['id']
			: false;
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
		$timestamp = wfTimestampNow();
		wfDebug( "Block::isExpired() checking current " . $timestamp . " vs $this->mExpiry\n" );

		if ( !$this->mExpiry ) {
			return false;
		} else {
			return $timestamp > $this->mExpiry;
		}
	}

	/**
	 * Is the block address valid (i.e. not a null string?)
	 * @return Boolean
	 */
	public function isValid() {
		return $this->getTarget() != null;
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
				),
				array( /* WHERE */
					'ipb_address' => (string)$this->getTarget()
				),
				__METHOD__
			);
		}
	}

	/**
	 * Get the IP address at the start of the range in Hex form
	 * @return String IP in Hex form
	 */
	public function getRangeStart() {
		switch( $this->type ) {
			case self::TYPE_USER:
				return '';
			case self::TYPE_IP:
				return IP::toHex( $this->target );
			case self::TYPE_RANGE:
				list( $start, /*...*/ ) = IP::parseRange( $this->target );
				return $start;
			default: throw new MWException( "Block with invalid type" );
		}
	}

	/**
	 * Get the IP address at the start of the range in Hex form
	 * @return String IP in Hex form
	 */
	public function getRangeEnd() {
		switch( $this->type ) {
			case self::TYPE_USER:
				return '';
			case self::TYPE_IP:
				return IP::toHex( $this->target );
			case self::TYPE_RANGE:
				list( /*...*/, $end ) = IP::parseRange( $this->target );
				return $end;
			default: throw new MWException( "Block with invalid type" );
		}
	}

	/**
	 * Get the user id of the blocking sysop
	 *
	 * @return Integer (0 for foreign users)
	 */
	public function getBy() {
		$blocker = $this->getBlocker();
		return ( $blocker instanceof User )
			? $blocker->getId()
			: 0;
	}

	/**
	 * Get the username of the blocking sysop
	 *
	 * @return String
	 */
	public function getByName() {
		$blocker = $this->getBlocker();
		return ( $blocker instanceof User )
			? $blocker->getName()
			: (string)$blocker; // username
	}

	/**
	 * Get the block ID
	 * @return int
	 */
	public function getId() {
		return $this->mId;
	}

	/**
	 * Get/set the SELECT ... FOR UPDATE flag
	 * @deprecated since 1.18
	 *
	 * @param $x Bool
	 */
	public function forUpdate( $x = null ) {
		# noop
	}

	/**
	 * Get/set a flag determining whether the master is used for reads
	 *
	 * @param $x Bool
	 * @return Bool
	 */
	public function fromMaster( $x = null ) {
		return wfSetVar( $this->mFromMaster, $x );
	}

	/**
	 * Get/set whether the Block is a hardblock (affects logged-in users on a given IP/range
	 * @param $x Bool
	 * @return  Bool
	 */
	public function isHardblock( $x = null ) {
		wfSetVar( $this->isHardblock, $x );

		# You can't *not* hardblock a user
		return $this->getType() == self::TYPE_USER
			? true
			: $this->isHardblock;
	}

	public function isAutoblocking( $x = null ) {
		wfSetVar( $this->isAutoblocking, $x );

		# You can't put an autoblock on an IP or range as we don't have any history to
		# look over to get more IPs from
		return $this->getType() == self::TYPE_USER
			? $this->isAutoblocking
			: false;
	}

	/**
	 * Get/set whether the Block prevents a given action
	 * @param $action String
	 * @param $x Bool
	 * @return Bool
	 */
	public function prevents( $action, $x = null ) {
		switch( $action ) {
			case 'edit':
				# For now... <evil laugh>
				return true;

			case 'createaccount':
				return wfSetVar( $this->mCreateAccount, $x );

			case 'sendemail':
				return wfSetVar( $this->mBlockEmail, $x );

			case 'editownusertalk':
				return wfSetVar( $this->mDisableUsertalk, $x );

			default:
				return null;
		}
	}

	/**
	 * Get the block name, but with autoblocked IPs hidden as per standard privacy policy
	 * @return String, text is escaped
	 */
	public function getRedactedName() {
		if ( $this->mAuto ) {
			return Html::rawElement(
				'span',
				array( 'class' => 'mw-autoblockid' ),
				wfMessage( 'autoblockid', $this->mId )
			);
		} else {
			return htmlspecialchars( $this->getTarget() );
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
	 * @param $timestampType Int Requested timestamp format
	 * @return String
	 * @deprecated since 1.18; use $wgLang->formatExpiry() instead
	 */
	public static function decodeExpiry( $expiry, $timestampType = TS_MW ) {
		global $wgContLang;
		return $wgContLang->formatExpiry( $expiry, $timestampType );
	}

	/**
	 * Get a timestamp of the expiry for autoblocks
	 *
	 * @param $timestamp String|Int
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
		$dbw->delete( 'ipblocks',
			array( 'ipb_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ), __METHOD__ );
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
	 * @param $specificTarget String|User|Int a block target, which may be one of several types:
	 *     * A user to block, in which case $target will be a User
	 *     * An IP to block, in which case $target will be a User generated by using
	 *       User::newFromName( $ip, false ) to turn off name validation
	 *     * An IP range, in which case $target will be a String "123.123.123.123/18" etc
	 *     * The ID of an existing block, in the format "#12345" (since pure numbers are valid
	 *       usernames
	 *     Calling this with a user, IP address or range will not select autoblocks, and will
	 *     only select a block where the targets match exactly (so looking for blocks on
	 *     1.2.3.4 will not select 1.2.0.0/16 or even 1.2.3.4/32)
	 * @param $vagueTarget String|User|Int as above, but we will search for *any* block which
	 *     affects that target (so for an IP address, get ranges containing that IP; and also
	 *     get any relevant autoblocks). Leave empty or blank to skip IP-based lookups.
	 * @param $fromMaster Bool whether to use the DB_MASTER database
	 * @return Block|null (null if no relevant block could be found).  The target and type
	 *     of the returned Block will refer to the actual block which was found, which might
	 *     not be the same as the target you gave if you used $vagueTarget!
	 */
	public static function newFromTarget( $specificTarget, $vagueTarget = null, $fromMaster = false ) {

		list( $target, $type ) = self::parseTarget( $specificTarget );
		if( $type == Block::TYPE_ID || $type == Block::TYPE_AUTO ){
			return Block::newFromID( $target );

		} elseif( $target === null && $vagueTarget == '' ){
			# We're not going to find anything useful here
			# Be aware that the == '' check is explicit, since empty values will be
			# passed by some callers (bug 29116)
			return null;

		} elseif( in_array( $type, array( Block::TYPE_USER, Block::TYPE_IP, Block::TYPE_RANGE, null ) ) ) {
			$block = new Block();
			$block->fromMaster( $fromMaster );

			if( $type !== null ){
				$block->setTarget( $target );
			}

			if( $block->newLoad( $vagueTarget ) ){
				return $block;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	/**
	 * From an existing Block, get the target and the type of target.  Note that it is
	 * always safe to treat the target as a string; for User objects this will return
	 * User::__toString() which in turn gives User::getName().
	 *
	 * @param $target String|Int|User
	 * @return array( User|String, Block::TYPE_ constant )
	 */
	public static function parseTarget( $target ) {
		$target = trim( $target );

		# We may have been through this before
		if( $target instanceof User ){
			if( IP::isValid( $target->getName() ) ){
				return array( $target, self::TYPE_IP );
			} else {
				return array( $target, self::TYPE_USER );
			}
		} elseif( $target === null ){
			return array( null, null );
		}

		if ( IP::isValid( $target ) ) {
			# We can still create a User if it's an IP address, but we need to turn
			# off validation checking (which would exclude IP addresses)
			return array(
				User::newFromName( IP::sanitizeIP( $target ), false ),
				Block::TYPE_IP
			);

		} elseif ( IP::isValidBlock( $target ) ) {
			# Can't create a User from an IP range
			return array( IP::sanitizeRange( $target ), Block::TYPE_RANGE );
		}

		# Consider the possibility that this is not a username at all
		# but actually an old subpage (bug #29797)
		if( strpos( $target, '/' ) !== false ){
			# An old subpage, drill down to the user behind it
			$parts = explode( '/', $target );
			$target = $parts[0];
		}

		$userObj = User::newFromName( $target );
		if ( $userObj instanceof User ) {
			# Note that since numbers are valid usernames, a $target of "12345" will be
			# considered a User.  If you want to pass a block ID, prepend a hash "#12345",
			# since hash characters are not valid in usernames or titles generally.
			return array( $userObj, Block::TYPE_USER );

		} elseif ( preg_match( '/^#\d+$/', $target ) ) {
			# Autoblock reference in the form "#12345"
			return array( substr( $target, 1 ), Block::TYPE_AUTO );

		} else {
			# WTF?
			return array( null, null );
		}
	}

	/**
	 * Get the type of target for this particular block
	 * @return Block::TYPE_ constant, will never be TYPE_ID
	 */
	public function getType() {
		return $this->mAuto
			? self::TYPE_AUTO
			: $this->type;
	}

	/**
	 * Get the target and target type for this particular Block.  Note that for autoblocks,
	 * this returns the unredacted name; frontend functions need to call $block->getRedactedName()
	 * in this situation.
	 * @return array( User|String, Block::TYPE_ constant )
	 * @todo FIXME: This should be an integral part of the Block member variables
	 */
	public function getTargetAndType() {
		return array( $this->getTarget(), $this->getType() );
	}

	/**
	 * Get the target for this particular Block.  Note that for autoblocks,
	 * this returns the unredacted name; frontend functions need to call $block->getRedactedName()
	 * in this situation.
	 * @return User|String
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * Set the target for this block, and update $this->type accordingly
	 * @param $target Mixed
	 */
	public function setTarget( $target ){
		list( $this->target, $this->type ) = self::parseTarget( $target );
	}

	/**
	 * Get the user who implemented this block
	 * @return User|string Local User object or string for a foreign user
	 */
	public function getBlocker(){
		return $this->blocker;
	}

	/**
	 * Set the user who implemented (or will implement) this block
	 * @param $user User|string Local User object or username string for foriegn users
	 */
	public function setBlocker( $user ){
		$this->blocker = $user;
	}
}
