<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:Longpages
 *
 * @ingroup SpecialPage
 */
class SpecialLongPages extends SpecialShortPages {

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct(
			$namespaceInfo,
			$dbProvider,
			$linkBatchFactory
		);
		$this->mName = 'Longpages';
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return true;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialLongPages::class, 'SpecialLongPages' );
