<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\User\UserFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * A factory for ChangeTagsStore instances.
 *
 * @since 1.46
 * @ingroup ChangeTags
 */
class ChangeTagsStoreFactory {

	public function __construct(
		private IConnectionProvider $dbProvider,
		private NameTableStoreFactory $changeTagDefStoreFactory,
		private WANObjectCache $wanCache,
		private HookContainer $hookContainer,
		private LoggerInterface $logger,
		private UserFactory $userFactory,
		private ServiceOptions $options
	) {
	}

	/**
	 * Returns a ChangeTagsStore for the requested wiki.
	 */
	public function getChangeTagsStore( string|false $wiki = false ): ChangeTagsStore {
		return new ChangeTagsStore(
			$this->dbProvider,
			$this->changeTagDefStoreFactory->getChangeTagDef( $wiki ),
			$this->wanCache,
			$this->hookContainer,
			$this->logger,
			$this->userFactory,
			$this->options,
			$wiki
		);
	}
}
