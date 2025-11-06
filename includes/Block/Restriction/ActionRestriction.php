<?php
/**
 * A block restriction object of type 'Action'.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block\Restriction;

use MediaWiki\Title\Title;

/**
 * Restriction for partial blocks of actions.
 *
 * @since 1.37
 */
class ActionRestriction extends AbstractRestriction {

	/**
	 * @inheritDoc
	 */
	public const TYPE = 'action';

	/**
	 * @inheritDoc
	 */
	public const TYPE_ID = 3;

	/**
	 * @inheritDoc
	 */
	public function matches( Title $title ) {
		// Action blocks don't apply to particular titles. For example,
		// if a block only blocked uploading, the target would still be
		// allowed to edit any page.
		return false;
	}

}
