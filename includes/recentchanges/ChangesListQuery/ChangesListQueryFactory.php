<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use Wikimedia\Rdbms\IConnectionProvider;

/** @since 1.45 */
class ChangesListQueryFactory {
	public const CONSTRUCTOR_OPTIONS = ChangesListQuery::CONSTRUCTOR_OPTIONS;

	public function __construct(
		private ServiceOptions $config,
		private RecentChangeLookup $recentChangeLookup,
		private WatchedItemStoreInterface $watchedItemStore,
		private TempUserConfig $tempUserConfig,
		private UserFactory $userFactory,
		private IConnectionProvider $connectionProvider,
	) {
	}

	public function newQuery(): ChangesListQuery {
		return new ChangesListQuery(
			$this->config,
			$this->recentChangeLookup,
			$this->watchedItemStore,
			$this->tempUserConfig,
			$this->userFactory,
			$this->connectionProvider->getReplicaDatabase()
		);
	}

	/**
	 * @internal
	 * @param TempUserConfig $tempUserConfig
	 */
	public function setTempUserConfig( TempUserConfig $tempUserConfig ) {
		$this->tempUserConfig = $tempUserConfig;
	}
}
