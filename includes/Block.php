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
	/** @var string */
	public $mReason;

	/** @var bool|string */
	public $mTimestamp;

	/** @var int */
	public $mAuto;

	/** @var bool|string */
	public $mExpiry;

	public $mHideName;

	/** @var int */
	public $mParentBlockId;

	/** @var int */
	protected $mId;

	/** @var bool */
	protected $mFromMaster;

	/** @var bool */
	protected $mBlockEmail;

	/** @var bool */
	protected $mDisableUsertalk;

	/** @var bool */
	protected $mCreateAccount;

	/** @var User|string */
	protected $target;

	/** @var int Hack for foreign blocking (CentralAuth) */
	protected $forcedTargetID;

	/** @var int Block::TYPE_ constant. Can only be USER, IP or RANGE internally */
	protected $type;

	/** @var User */
	protected $blocker;

	/** @var bool */
	protected $isHardblock = true;

	/** @var bool */
	protected $isAutoblocking = true;

	# TYPE constants
	const TYPE_USER = 1;
	const TYPE_IP = 2;
	const TYPE_RANGE = 3;
	const TYPE_AUTO = 4;
	const TYPE_ID = 5;

	/**
	 * @todo FIXME: Don't know what the best format to have for this constructor
	 *   is, but fourteen optional parameters certainly isn't it.
	 * @param string $address
	 * @param int $user
	 * @param int $by
	 * @param string $reason
	 * @param mixed $timestamp
	 * @param int $auto
	 * @param string $expiry
	 * @param int $anonOnly
	 * @param int $createAccount
	 * @param int $enableAutoblock
	 * @param int $hideName
	 * @param int $blockEmail
	 * @param int $allowUsertalk
	 * @param string $byText
	 */
	function __construct( $address = '', $user = 0, $by = 0, $reason = '',
		$timestamp = 0, $auto = 0, $expiry = '', $anonOnly = 0, $createAccount = 0, $enableAutoblock = 0,
		$hideName = 0, $blockEmail = 0, $allowUsertalk = 0, $byText = ''
	) {
		if ( $timestamp === 0 ) {
			$timestamp = wfTimestampNow();
		}

		if ( count( func_get_args() ) > 0 ) {
			# Soon... :D
			# wfDeprecated( __METHOD__ . " with arguments" );
		}

		$this->setTarget( $address );
		if ( $this->target instanceof User && $user ) {
			$this->forcedTargetID = $user; // needed for foreign users
		}
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
		if ( $expiry == 'infinity' || $expiry == wfGetDB( DB_SLAVE )->getInfinity() ) {
			$this->mExpiry = 'infinity';
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
	 * Load a blocked user from their block id.
	 *
	 * @param int $id Block id to search for
	 * @return Block|null
	 */
	public static function newFromID( $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow(
			'ipblocks',
			self::selectFields(),
			array( 'ipb_id' => $id ),
			__METHOD__
		);
		if ( $res ) {
			return self::newFromRow( $res );
		} else {
			return null;
		}
	}

	/**
	 * Return the list of ipblocks fields that should be selected to create
	 * a new block.
	 * @return array
	 */
	public static function selectFields() {
		return array(
			'ipb_id',
			'ipb_address',
			'ipb_by',
			'ipb_by_text',
			'ipb_reason',
			'ipb_timestamp',
			'ipb_auto',
			'ipb_anon_only',
			'ipb_create_account',
			'ipb_enable_autoblock',
			'ipb_expiry',
			'ipb_deleted',
			'ipb_block_email',
			'ipb_allow_usertalk',
			'ipb_parent_block_id',
		);
	}

	/**
	 * Check if two blocks are effectively equal.  Doesn't check irrelevant things like
	 * the blocking user or the block timestamp, only things which affect the blocked user
	 *
	 * @param Block $block
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
	 * Load a block from the database which affects the already-set $this->target:
	 *     1) A block directly on the given user or IP
	 *     2) A rangeblock encompassing the given IP (smallest first)
	 *     3) An autoblock on the given IP
	 * @param User|string $vagueTarget Also search for blocks affecting this target.  Doesn't
	 *     make any sense to use TYPE_AUTO / TYPE_ID here. Leave blank to skip IP lookups.
	 * @throws MWException
	 * @return bool Whether a relevant block was found
	 */
	protected function newLoad( $vagueTarget = null ) {
		$db = wfGetDB( $this->mFromMaster ? DB_MASTER : DB_SLAVE );

		if ( $this->type !== null ) {
			$conds = array(
				'ipb_address' => array( (string)$this->target ),
			);
		} else {
			$conds = array( 'ipb_address' => array() );
		}

		# Be aware that the != '' check is explicit, since empty values will be
		# passed by some callers (bug 29116)
		if ( $vagueTarget != '' ) {
			list( $target, $type ) = self::parseTarget( $vagueTarget );
			switch ( $type ) {
				case self::TYPE_USER:
					# Slightly weird, but who are we to argue?
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

		$res = $db->select( 'ipblocks', self::selectFields(), $conds, __METHOD__ );

		# This result could contain a block on the user, a block on the IP, and a russian-doll
		# set of rangeblocks.  We want to choose the most specific one, so keep a leader board.
		$bestRow = null;

		# Lower will be better
		$bestBlockScore = 100;

		# This is begging for $this = $bestBlock, but that's not allowed in PHP :(
		$bestBlockPreventsEdit = null;

		foreach ( $res as $row ) {
			$block = self::newFromRow( $row );

			# Don't use expired blocks
			if ( $block->deleteIfExpired() ) {
				continue;
			}

			# Don't use anon only blocks on users
			if ( $this->type == self::TYPE_USER && !$block->isHardblock() ) {
				continue;
			}

			if ( $block->getType() == self::TYPE_RANGE ) {
				# This is the number of bits that are allowed to vary in the block, give
				# or take some floating point errors
				$end = wfBaseconvert( $block->getRangeEnd(), 16, 10 );
				$start = wfBaseconvert( $block->getRangeStart(), 16, 10 );
				$size = log( $end - $start + 1, 2 );

				# This has the nice property that a /32 block is ranked equally with a
				# single-IP block, which is exactly what it is...
				$score = self::TYPE_RANGE - 1 + ( $size / 128 );

			} else {
				$score = $block->getType();
			}

			if ( $score < $bestBlockScore ) {
				$bestBlockScore = $score;
				$bestRow = $row;
				$bestBlockPreventsEdit = $block->prevents( 'edit' );
			}
		}

		if ( $bestRow !== null ) {
			$this->initFromRow( $bestRow );
			$this->prevents( 'edit', $bestBlockPreventsEdit );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get a set of SQL conditions which will select rangeblocks encompassing a given range
	 * @param string $start Hexadecimal IP representation
	 * @param string $end Hexadecimal IP representation, or null to use $start = $end
	 * @return string
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
	 * @param string $hex Hexadecimal IP representation
	 * @return string
	 */
	protected static function getIpFragment( $hex ) {
		global $wgBlockCIDRLimit;
		if ( substr( $hex, 0, 3 ) == 'v6-' ) {
			return 'v6-' . substr( substr( $hex, 3 ), 0, floor( $wgBlockCIDRLimit['IPv6'] / 4 ) );
		} else {
			return substr( $hex, 0, floor( $wgBlockCIDRLimit['IPv4'] / 4 ) );
		}
	}

	/**
	 * Given a database row from the ipblocks table, initialize
	 * member variables
	 * @param stdClass $row A row from the ipblocks table
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
		$this->mParentBlockId = $row->ipb_parent_block_id;

		// I wish I didn't have to do this
		$db = wfGetDB( DB_SLAVE );
		if ( $row->ipb_expiry == $db->getInfinity() ) {
			$this->mExpiry = 'infinity';
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
	 * @param stdClass $row Row from the ipblocks table
	 * @return Block
	 */
	public static function newFromRow( $row ) {
		$block = new Block;
		$block->initFromRow( $row );
		return $block;
	}

	/**
	 * Delete the row from the IP blocks table.
	 *
	 * @throws MWException
	 * @return bool
	 */
	public function delete() {
		if ( wfReadOnly() ) {
			return false;
		}

		if ( !$this->getId() ) {
			throw new MWException( "Block::delete() requires that the mId member be filled\n" );
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'ipblocks', array( 'ipb_parent_block_id' => $this->getId() ), __METHOD__ );
		$dbw->delete( 'ipblocks', array( 'ipb_id' => $this->getId() ), __METHOD__ );

		return $dbw->affectedRows() > 0;
	}

	/**
	 * Insert a block into the block table. Will fail if there is a conflicting
	 * block (same name and options) already in the database.
	 *
	 * @param DatabaseBase $dbw If you have one available
	 * @return bool|array False on failure, assoc array on success:
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
		$row['ipb_id'] = $dbw->nextSequenceValue( "ipblocks_ipb_id_seq" );

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
	 * @return bool|array False on failure, array on success:
	 *   ('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function update() {
		wfDebug( "Block::update; timestamp {$this->mTimestamp}\n" );
		$dbw = wfGetDB( DB_MASTER );

		$dbw->startAtomic( __METHOD__ );

		$dbw->update(
			'ipblocks',
			$this->getDatabaseArray( $dbw ),
			array( 'ipb_id' => $this->getId() ),
			__METHOD__
		);

		$affected = $dbw->affectedRows();

		if ( $this->isAutoblocking() ) {
			// update corresponding autoblock(s) (bug 48813)
			$dbw->update(
				'ipblocks',
				$this->getAutoblockUpdateArray(),
				array( 'ipb_parent_block_id' => $this->getId() ),
				__METHOD__
			);
		} else {
			// autoblock no longer required, delete corresponding autoblock(s)
			$dbw->delete(
				'ipblocks',
				array( 'ipb_parent_block_id' => $this->getId() ),
				__METHOD__
			);
		}

		$dbw->endAtomic( __METHOD__ );

		if ( $affected ) {
			$auto_ipd_ids = $this->doRetroactiveAutoblock();
			return array( 'id' => $this->mId, 'autoIds' => $auto_ipd_ids );
		}

		return false;
	}

	/**
	 * Get an array suitable for passing to $dbw->insert() or $dbw->update()
	 * @param DatabaseBase $db
	 * @return array
	 */
	protected function getDatabaseArray( $db = null ) {
		if ( !$db ) {
			$db = wfGetDB( DB_SLAVE );
		}
		$expiry = $db->encodeExpiry( $this->mExpiry );

		if ( $this->forcedTargetID ) {
			$uid = $this->forcedTargetID;
		} else {
			$uid = $this->target instanceof User ? $this->target->getID() : 0;
		}

		$a = array(
			'ipb_address'          => (string)$this->target,
			'ipb_user'             => $uid,
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
			'ipb_deleted'          => intval( $this->mHideName ), // typecast required for SQLite
			'ipb_block_email'      => $this->prevents( 'sendemail' ),
			'ipb_allow_usertalk'   => !$this->prevents( 'editownusertalk' ),
			'ipb_parent_block_id'  => $this->mParentBlockId
		);

		return $a;
	}

	/**
	 * @return array
	 */
	protected function getAutoblockUpdateArray() {
		return array(
			'ipb_by'               => $this->getBy(),
			'ipb_by_text'          => $this->getByName(),
			'ipb_reason'           => $this->mReason,
			'ipb_create_account'   => $this->prevents( 'createaccount' ),
			'ipb_deleted'          => (int)$this->mHideName, // typecast required for SQLite
			'ipb_allow_usertalk'   => !$this->prevents( 'editownusertalk' ),
		);
	}

	/**
	 * Retroactively autoblocks the last IP used by the user (if it is a user)
	 * blocked by this Block.
	 *
	 * @return array Block IDs of retroactive autoblocks made
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
	 * @param array &$blockIds
	 */
	protected static function defaultRetroactiveAutoblock( Block $block, array &$blockIds ) {
		global $wgPutIPinRC;

		// No IPs are in recentchanges table, so nothing to select
		if ( !$wgPutIPinRC ) {
			return;
		}

		$dbr = wfGetDB( DB_SLAVE );

		$options = array( 'ORDER BY' => 'rc_timestamp DESC' );
		$conds = array( 'rc_user_text' => (string)$block->getTarget() );

		// Just the last IP used.
		$options['LIMIT'] = 1;

		$res = $dbr->select( 'recentchanges', array( 'rc_ip' ), $conds,
			__METHOD__, $options );

		if ( !$res->numRows() ) {
			# No results, don't autoblock anything
			wfDebug( "No IP found to retroactively autoblock\n" );
		} else {
			foreach ( $res as $row ) {
				if ( $row->rc_ip ) {
					$id = $block->doAutoblock( $row->rc_ip );
					if ( $id ) {
						$blockIds[] = $id;
					}
				}
			}
		}
	}

	/**
	 * Checks whether a given IP is on the autoblock whitelist.
	 * TODO: this probably belongs somewhere else, but not sure where...
	 *
	 * @param string $ip The IP to check
	 * @return bool
	 */
	public static function isWhitelistedFromAutoblocks( $ip ) {
		global $wgMemc;

		// Try to get the autoblock_whitelist from the cache, as it's faster
		// than getting the msg raw and explode()'ing it.
		$key = wfMemcKey( 'ipb', 'autoblock', 'whitelist' );
		$lines = $wgMemc->get( $key );
		if ( !$lines ) {
			$lines = explode( "\n", wfMessage( 'autoblock_whitelist' )->inContentLanguage()->plain() );
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
	 * @param string $autoblockIP The IP to autoblock.
	 * @return int|bool Block ID if an autoblock was inserted, false if not.
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
			if ( $ipblock->mAuto && // @todo Why not compare $ipblock->mExpiry?
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
		$autoblock->mReason = wfMessage( 'autoblocker', $this->getTarget(), $this->mReason )
			->inContentLanguage()->plain();
		$timestamp = wfTimestampNow();
		$autoblock->mTimestamp = $timestamp;
		$autoblock->mAuto = 1;
		$autoblock->prevents( 'createaccount', $this->prevents( 'createaccount' ) );
		# Continue suppressing the name if needed
		$autoblock->mHideName = $this->mHideName;
		$autoblock->prevents( 'editownusertalk', $this->prevents( 'editownusertalk' ) );
		$autoblock->mParentBlockId = $this->mId;

		if ( $this->mExpiry == 'infinity' ) {
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
	 * @return bool
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
	 * @return bool
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
	 * @return bool
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
	 * @throws MWException
	 * @return string IP in Hex form
	 */
	public function getRangeStart() {
		switch ( $this->type ) {
			case self::TYPE_USER:
				return '';
			case self::TYPE_IP:
				return IP::toHex( $this->target );
			case self::TYPE_RANGE:
				list( $start, /*...*/ ) = IP::parseRange( $this->target );
				return $start;
			default:
				throw new MWException( "Block with invalid type" );
		}
	}

	/**
	 * Get the IP address at the end of the range in Hex form
	 * @throws MWException
	 * @return string IP in Hex form
	 */
	public function getRangeEnd() {
		switch ( $this->type ) {
			case self::TYPE_USER:
				return '';
			case self::TYPE_IP:
				return IP::toHex( $this->target );
			case self::TYPE_RANGE:
				list( /*...*/, $end ) = IP::parseRange( $this->target );
				return $end;
			default:
				throw new MWException( "Block with invalid type" );
		}
	}

	/**
	 * Get the user id of the blocking sysop
	 *
	 * @return int (0 for foreign users)
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
	 * @return string
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
	 * Get/set a flag determining whether the master is used for reads
	 *
	 * @param bool $x
	 * @return bool
	 */
	public function fromMaster( $x = null ) {
		return wfSetVar( $this->mFromMaster, $x );
	}

	/**
	 * Get/set whether the Block is a hardblock (affects logged-in users on a given IP/range
	 * @param bool $x
	 * @return bool
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
	 * @param string $action
	 * @param bool $x
	 * @return bool
	 */
	public function prevents( $action, $x = null ) {
		switch ( $action ) {
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
	 * @return string Text is escaped
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
	 * Get a timestamp of the expiry for autoblocks
	 *
	 * @param string|int $timestamp
	 * @return string
	 */
	public static function getAutoblockExpiry( $timestamp ) {
		global $wgAutoblockExpiry;

		return wfTimestamp( TS_MW, wfTimestamp( TS_UNIX, $timestamp ) + $wgAutoblockExpiry );
	}

	/**
	 * Purge expired blocks from the ipblocks table
	 */
	public static function purgeExpired() {
		if ( wfReadOnly() ) {
			return;
		}

		$method = __METHOD__;
		$dbw = wfGetDB( DB_MASTER );
		$dbw->onTransactionIdle( function () use ( $dbw, $method ) {
			$dbw->delete( 'ipblocks',
				array( 'ipb_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ), $method );
		} );
	}

	/**
	 * Given a target and the target's type, get an existing Block object if possible.
	 * @param string|User|int $specificTarget A block target, which may be one of several types:
	 *     * A user to block, in which case $target will be a User
	 *     * An IP to block, in which case $target will be a User generated by using
	 *       User::newFromName( $ip, false ) to turn off name validation
	 *     * An IP range, in which case $target will be a String "123.123.123.123/18" etc
	 *     * The ID of an existing block, in the format "#12345" (since pure numbers are valid
	 *       usernames
	 *     Calling this with a user, IP address or range will not select autoblocks, and will
	 *     only select a block where the targets match exactly (so looking for blocks on
	 *     1.2.3.4 will not select 1.2.0.0/16 or even 1.2.3.4/32)
	 * @param string|User|int $vagueTarget As above, but we will search for *any* block which
	 *     affects that target (so for an IP address, get ranges containing that IP; and also
	 *     get any relevant autoblocks). Leave empty or blank to skip IP-based lookups.
	 * @param bool $fromMaster Whether to use the DB_MASTER database
	 * @return Block|null (null if no relevant block could be found).  The target and type
	 *     of the returned Block will refer to the actual block which was found, which might
	 *     not be the same as the target you gave if you used $vagueTarget!
	 */
	public static function newFromTarget( $specificTarget, $vagueTarget = null, $fromMaster = false ) {

		list( $target, $type ) = self::parseTarget( $specificTarget );
		if ( $type == Block::TYPE_ID || $type == Block::TYPE_AUTO ) {
			return Block::newFromID( $target );

		} elseif ( $target === null && $vagueTarget == '' ) {
			# We're not going to find anything useful here
			# Be aware that the == '' check is explicit, since empty values will be
			# passed by some callers (bug 29116)
			return null;

		} elseif ( in_array(
			$type,
			array( Block::TYPE_USER, Block::TYPE_IP, Block::TYPE_RANGE, null ) )
		) {
			$block = new Block();
			$block->fromMaster( $fromMaster );

			if ( $type !== null ) {
				$block->setTarget( $target );
			}

			if ( $block->newLoad( $vagueTarget ) ) {
				return $block;
			}
		}
		return null;
	}

	/**
	 * Get all blocks that match any IP from an array of IP addresses
	 *
	 * @param array $ipChain List of IPs (strings), usually retrieved from the
	 *	   X-Forwarded-For header of the request
	 * @param bool $isAnon Exclude anonymous-only blocks if false
	 * @param bool $fromMaster Whether to query the master or slave database
	 * @return array Array of Blocks
	 * @since 1.22
	 */
	public static function getBlocksForIPList( array $ipChain, $isAnon, $fromMaster = false ) {
		if ( !count( $ipChain ) ) {
			return array();
		}

		wfProfileIn( __METHOD__ );
		$conds = array();
		foreach ( array_unique( $ipChain ) as $ipaddr ) {
			# Discard invalid IP addresses. Since XFF can be spoofed and we do not
			# necessarily trust the header given to us, make sure that we are only
			# checking for blocks on well-formatted IP addresses (IPv4 and IPv6).
			# Do not treat private IP spaces as special as it may be desirable for wikis
			# to block those IP ranges in order to stop misbehaving proxies that spoof XFF.
			if ( !IP::isValid( $ipaddr ) ) {
				continue;
			}
			# Don't check trusted IPs (includes local squids which will be in every request)
			if ( IP::isTrustedProxy( $ipaddr ) ) {
				continue;
			}
			# Check both the original IP (to check against single blocks), as well as build
			# the clause to check for rangeblocks for the given IP.
			$conds['ipb_address'][] = $ipaddr;
			$conds[] = self::getRangeCond( IP::toHex( $ipaddr ) );
		}

		if ( !count( $conds ) ) {
			wfProfileOut( __METHOD__ );
			return array();
		}

		if ( $fromMaster ) {
			$db = wfGetDB( DB_MASTER );
		} else {
			$db = wfGetDB( DB_SLAVE );
		}
		$conds = $db->makeList( $conds, LIST_OR );
		if ( !$isAnon ) {
			$conds = array( $conds, 'ipb_anon_only' => 0 );
		}
		$selectFields = array_merge(
			array( 'ipb_range_start', 'ipb_range_end' ),
			Block::selectFields()
		);
		$rows = $db->select( 'ipblocks',
			$selectFields,
			$conds,
			__METHOD__
		);

		$blocks = array();
		foreach ( $rows as $row ) {
			$block = self::newFromRow( $row );
			if ( !$block->deleteIfExpired()  ) {
				$blocks[] = $block;
			}
		}

		wfProfileOut( __METHOD__ );
		return $blocks;
	}

	/**
	 * From a list of multiple blocks, find the most exact and strongest Block.
	 * The logic for finding the "best" block is:
	 *  - Blocks that match the block's target IP are preferred over ones in a range
	 *  - Hardblocks are chosen over softblocks that prevent account creation
	 *  - Softblocks that prevent account creation are chosen over other softblocks
	 *  - Other softblocks are chosen over autoblocks
	 *  - If there are multiple exact or range blocks at the same level, the one chosen
	 *    is random
	 *
	 * @param array $blocks Array of blocks
	 * @param array $ipChain List of IPs (strings). This is used to determine how "close"
	 * 	  a block is to the server, and if a block matches exactly, or is in a range.
	 *	  The order is furthest from the server to nearest e.g., (Browser, proxy1, proxy2,
	 *	  local-squid, ...)
	 * @return Block|null The "best" block from the list
	 */
	public static function chooseBlock( array $blocks, array $ipChain ) {
		if ( !count( $blocks ) ) {
			return null;
		} elseif ( count( $blocks ) == 1 ) {
			return $blocks[0];
		}

		wfProfileIn( __METHOD__ );

		// Sort hard blocks before soft ones and secondarily sort blocks
		// that disable account creation before those that don't.
		usort( $blocks, function ( Block $a, Block $b ) {
			$aWeight = (int)$a->isHardblock() . (int)$a->prevents( 'createaccount' );
			$bWeight = (int)$b->isHardblock() . (int)$b->prevents( 'createaccount' );
			return strcmp( $bWeight, $aWeight ); // highest weight first
		} );

		$blocksListExact = array(
			'hard' => false,
			'disable_create' => false,
			'other' => false,
			'auto' => false
		);
		$blocksListRange = array(
			'hard' => false,
			'disable_create' => false,
			'other' => false,
			'auto' => false
		);
		$ipChain = array_reverse( $ipChain );

		foreach ( $blocks as $block ) {
			// Stop searching if we have already have a "better" block. This
			// is why the order of the blocks matters
			if ( !$block->isHardblock() && $blocksListExact['hard'] ) {
				break;
			} elseif ( !$block->prevents( 'createaccount' ) && $blocksListExact['disable_create'] ) {
				break;
			}

			foreach ( $ipChain as $checkip ) {
				$checkipHex = IP::toHex( $checkip );
				if ( (string)$block->getTarget() === $checkip ) {
					if ( $block->isHardblock() ) {
						$blocksListExact['hard'] = $blocksListExact['hard'] ?: $block;
					} elseif ( $block->prevents( 'createaccount' ) ) {
						$blocksListExact['disable_create'] = $blocksListExact['disable_create'] ?: $block;
					} elseif ( $block->mAuto ) {
						$blocksListExact['auto'] = $blocksListExact['auto'] ?: $block;
					} else {
						$blocksListExact['other'] = $blocksListExact['other'] ?: $block;
					}
					// We found closest exact match in the ip list, so go to the next Block
					break;
				} elseif ( array_filter( $blocksListExact ) == array()
					&& $block->getRangeStart() <= $checkipHex
					&& $block->getRangeEnd() >= $checkipHex
				) {
					if ( $block->isHardblock() ) {
						$blocksListRange['hard'] = $blocksListRange['hard'] ?: $block;
					} elseif ( $block->prevents( 'createaccount' ) ) {
						$blocksListRange['disable_create'] = $blocksListRange['disable_create'] ?: $block;
					} elseif ( $block->mAuto ) {
						$blocksListRange['auto'] = $blocksListRange['auto'] ?: $block;
					} else {
						$blocksListRange['other'] = $blocksListRange['other'] ?: $block;
					}
					break;
				}
			}
		}

		if ( array_filter( $blocksListExact ) == array() ) {
			$blocksList = &$blocksListRange;
		} else {
			$blocksList = &$blocksListExact;
		}

		$chosenBlock = null;
		if ( $blocksList['hard'] ) {
			$chosenBlock = $blocksList['hard'];
		} elseif ( $blocksList['disable_create'] ) {
			$chosenBlock = $blocksList['disable_create'];
		} elseif ( $blocksList['other'] ) {
			$chosenBlock = $blocksList['other'];
		} elseif ( $blocksList['auto'] ) {
			$chosenBlock = $blocksList['auto'];
		} else {
			wfProfileOut( __METHOD__ );
			throw new MWException( "Proxy block found, but couldn't be classified." );
		}

		wfProfileOut( __METHOD__ );
		return $chosenBlock;
	}

	/**
	 * From an existing Block, get the target and the type of target.
	 * Note that, except for null, it is always safe to treat the target
	 * as a string; for User objects this will return User::__toString()
	 * which in turn gives User::getName().
	 *
	 * @param string|int|User|null $target
	 * @return array( User|String|null, Block::TYPE_ constant|null )
	 */
	public static function parseTarget( $target ) {
		# We may have been through this before
		if ( $target instanceof User ) {
			if ( IP::isValid( $target->getName() ) ) {
				return array( $target, self::TYPE_IP );
			} else {
				return array( $target, self::TYPE_USER );
			}
		} elseif ( $target === null ) {
			return array( null, null );
		}

		$target = trim( $target );

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
		if ( strpos( $target, '/' ) !== false ) {
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
	 * @return int Block::TYPE_ constant, will never be TYPE_ID
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
	 * @return User|string
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @since 1.19
	 *
	 * @return mixed|string
	 */
	public function getExpiry() {
		return $this->mExpiry;
	}

	/**
	 * Set the target for this block, and update $this->type accordingly
	 * @param mixed $target
	 */
	public function setTarget( $target ) {
		list( $this->target, $this->type ) = self::parseTarget( $target );
	}

	/**
	 * Get the user who implemented this block
	 * @return User|string Local User object or string for a foreign user
	 */
	public function getBlocker() {
		return $this->blocker;
	}

	/**
	 * Set the user who implemented (or will implement) this block
	 * @param User|string $user Local User object or username string for foreign users
	 */
	public function setBlocker( $user ) {
		$this->blocker = $user;
	}

	/**
	 * Get the key and parameters for the corresponding error message.
	 *
	 * @since 1.22
	 * @param IContextSource $context
	 * @return array
	 */
	public function getPermissionsError( IContextSource $context ) {
		$blocker = $this->getBlocker();
		if ( $blocker instanceof User ) { // local user
			$blockerUserpage = $blocker->getUserPage();
			$link = "[[{$blockerUserpage->getPrefixedText()}|{$blockerUserpage->getText()}]]";
		} else { // foreign user
			$link = $blocker;
		}

		$reason = $this->mReason;
		if ( $reason == '' ) {
			$reason = $context->msg( 'blockednoreason' )->text();
		}

		/* $ip returns who *is* being blocked, $intended contains who was meant to be blocked.
		 * This could be a username, an IP range, or a single IP. */
		$intended = $this->getTarget();

		$lang = $context->getLanguage();
		return array(
			$this->mAuto ? 'autoblockedtext' : 'blockedtext',
			$link,
			$reason,
			$context->getRequest()->getIP(),
			$this->getByName(),
			$this->getId(),
			$lang->formatExpiry( $this->mExpiry ),
			(string)$intended,
			$lang->userTimeAndDate( $this->mTimestamp, $context->getUser() ),
		);
	}
}
