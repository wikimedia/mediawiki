<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Stats\StatsFactory;

/** @since 1.45 */
class ChangesListQueryFactory {
	public const CONSTRUCTOR_OPTIONS = ChangesListQuery::CONSTRUCTOR_OPTIONS;

	private TableStatsProvider $rcStats;

	public function __construct(
		private ServiceOptions $config,
		private RecentChangeLookup $recentChangeLookup,
		private WatchedItemStoreInterface $watchedItemStore,
		private TempUserConfig $tempUserConfig,
		private UserFactory $userFactory,
		private LinkTargetLookup $linkTargetLookup,
		private ChangeTagsStore $changeTagsStore,
		private StatsFactory $statsFactory,
		private NameTableStore $slotRoleStore,
		private LoggerInterface $logger,
		private IConnectionProvider $connectionProvider,
	) {
		$this->rcStats = new TableStatsProvider(
			$this->connectionProvider,
			'recentchanges',
			'rc_id'
		);
	}

	public function newQuery(): ChangesListQuery {
		return new ChangesListQuery(
			$this->config,
			$this->recentChangeLookup,
			$this->watchedItemStore,
			$this->tempUserConfig,
			$this->userFactory,
			$this->linkTargetLookup,
			$this->changeTagsStore,
			$this->statsFactory,
			$this->slotRoleStore,
			$this->logger,
			$this->connectionProvider->getReplicaDatabase(),
			$this->rcStats
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
