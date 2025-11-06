<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\Status\Status;
use MediaWiki\User\UserIdentity;

/**
 * Backend class for blocking utils
 *
 * This service should contain any methods that are useful
 * to more than one blocking-related class and don't fit any
 * other service.
 *
 * For now, this includes only
 * - block target parsing
 * - block target validation
 * - parsing the target and type of a block in the database
 *
 * @deprecated since 1.44 use BlockTargetFactory
 * @since 1.36
 */
class BlockUtils {
	private BlockTargetFactory $blockTargetFactory;

	public function __construct( BlockTargetFactory $blockTargetFactory ) {
		$this->blockTargetFactory = $blockTargetFactory;
	}

	/**
	 * From string specification or UserIdentity, get the block target and the
	 * type of target.
	 *
	 * Note that, except for null, it is always safe to treat the target
	 * as a string; for UserIdentityValue objects this will return
	 * UserIdentityValue::__toString() which in turn gives
	 * UserIdentityValue::getName().
	 *
	 * If the type is not null, it will be an AbstractBlock::TYPE_ constant.
	 *
	 * Since 1.42, it is no longer safe to pass a value from the database field
	 * ipb_address/bt_address to this method, since the username is normalized.
	 * Use parseBlockTargetRow() instead. (T346683)
	 *
	 * @param string|UserIdentity|null $target
	 * @return array [ UserIdentity|String|null, int|null ]
	 */
	public function parseBlockTarget( $target ): array {
		$targetObj = $this->blockTargetFactory->newFromLegacyUnion( $target );
		if ( $targetObj ) {
			return $targetObj->getLegacyTuple();
		} else {
			return [ null, null ];
		}
	}

	/**
	 * From a row which must contain bt_auto, bt_user, bt_address and bl_id,
	 * and optionally bt_user_text, determine the block target and type.
	 *
	 * @since 1.42
	 * @param \stdClass $row
	 * @return array [ UserIdentity|String|null, int|null ]
	 */
	public function parseBlockTargetRow( $row ) {
		$target = $this->blockTargetFactory->newFromRowRedacted( $row );
		if ( $target ) {
			return $target->getLegacyTuple();
		} else {
			return [ null, null ];
		}
	}

	/**
	 * Validate block target
	 *
	 * @param string|UserIdentity $value
	 *
	 * @return Status
	 */
	public function validateTarget( $value ): Status {
		$target = $this->blockTargetFactory->newFromLegacyUnion( $value );
		if ( $target ) {
			return Status::wrap( $target->validateForCreation() );
		} else {
			return Status::newFatal( 'badipaddress' );
		}
	}

}
