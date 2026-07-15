<?php
/**
 * A block restriction object for page creation in a namespace.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block\Restriction;

use MediaWiki\Title\Title;

class CreatePageNamespaceRestriction extends AbstractRestriction {

	/**
	 * @inheritDoc
	 */
	public const TYPE = 'createpage-ns';

	/**
	 * @inheritDoc
	 */
	public const TYPE_ID = 4;

	/**
	 * @inheritDoc
	 */
	public function matches( Title $title ) {
		return false;
	}

}
