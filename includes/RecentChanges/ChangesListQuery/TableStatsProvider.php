<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Cache and provide min/max ID and "size" (ID delta) of a table
 *
 * @since 1.45
 */
class TableStatsProvider {
	private ?int $minId;
	private ?int $maxId;
	private bool $loaded = false;

	public function __construct(
		private IConnectionProvider $connectionProvider,
		private string $tableName,
		private string $idField,
	) {
	}

	/**
	 * Estimate the number of rows in the table, using the difference of maximum
	 * and minimum primary keys.
	 *
	 * @return int
	 */
	public function getIdDelta() {
		$this->loadStats();
		return $this->maxId === null ? 0 : $this->maxId - $this->minId;
	}

	/**
	 * Get the minimum ID
	 *
	 * @return int|null
	 */
	public function getMinId() {
		$this->loadStats();
		return $this->minId;
	}

	/**
	 * Get the maximum ID
	 *
	 * @return int|null
	 */
	public function getMaxId() {
		$this->loadStats();
		return $this->maxId;
	}

	private function loadStats() {
		if ( $this->loaded ) {
			return;
		}
		$this->loaded = true;

		$info = $this->connectionProvider
			->getReplicaDatabase()
			->newSelectQueryBuilder()
			->select( [
				'min_id' => "MIN({$this->idField})",
				'max_id' => "MAX({$this->idField})",
			] )
			->from( $this->tableName )
			->caller( __METHOD__ )
			->fetchRow();
		if ( $info ) {
			$this->minId = (int)$info->min_id;
			$this->maxId = (int)$info->max_id;
		}
	}
}
