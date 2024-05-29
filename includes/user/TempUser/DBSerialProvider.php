<?php

namespace MediaWiki\User\TempUser;

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\RawSQLValue;

/**
 * Base class for serial acquisition code shared between core and CentralAuth.
 *
 * @since 1.39
 */
abstract class DBSerialProvider implements SerialProvider {
	/** @var int */
	private $numShards;

	/**
	 * @param array $config
	 *   - numShards (int, default 1): A small integer. This can be set to a
	 *     value greater than 1 to avoid acquiring a global lock when
	 *     allocating IDs, at the expense of making the IDs be non-monotonic.
	 */
	public function __construct( $config ) {
		$this->numShards = $config['numShards'] ?? 1;
	}

	public function acquireIndex( int $year = 0 ): int {
		if ( $this->numShards ) {
			$shard = mt_rand( 0, $this->numShards - 1 );
		} else {
			$shard = 0;
		}

		$dbw = $this->getDB();
		$table = $this->getTableName();
		$dbw->startAtomic( __METHOD__ );
		$dbw->newInsertQueryBuilder()
			->insertInto( $table )
			->row( [
				'uas_shard' => $shard,
				'uas_year' => $year,
				'uas_value' => 1
			] )
			->onDuplicateKeyUpdate()
			->uniqueIndexFields( [ 'uas_shard', 'uas_year' ] )
			->set( [ 'uas_value' => new RawSQLValue( 'uas_value+1' ) ] )
			->caller( __METHOD__ )->execute();
		$value = $dbw->newSelectQueryBuilder()
			->select( 'uas_value' )
			->from( $table )
			->where( [ 'uas_shard' => $shard ] )
			->andWhere( [ 'uas_year' => $year ] )
			->caller( __METHOD__ )
			->fetchField();
		$dbw->endAtomic( __METHOD__ );
		return $value * $this->numShards + $shard;
	}

	/**
	 * @return IDatabase
	 */
	abstract protected function getDB();

	/**
	 * @return string
	 */
	abstract protected function getTableName();
}
