<?php
/**
 * A block restriction object of type 'Namespace'.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block\Restriction;

use MediaWiki\Title\Title;

class NamespaceRestriction extends AbstractRestriction {

	/**
	 * @inheritDoc
	 */
	public const TYPE = 'ns';

	/**
	 * @inheritDoc
	 */
	public const TYPE_ID = 2;

	/**
	 * @inheritDoc
	 */
	public function matches( Title $title ) {
		return $this->getValue() === $title->getNamespace();
	}

}
