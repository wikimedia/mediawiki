<?php

namespace MediaWiki\Maintenance;

use Wikimedia\Rdbms\IDatabase;

/**
 * Update a database while optionally writing SQL that reverses the update to
 * a file.
 */
class UndoLog {
	private $file;
	private $dbw;

	/**
	 * @param string|null $fileName
	 * @param IDatabase $dbw
	 */
	public function __construct( $fileName, IDatabase $dbw ) {
		if ( $fileName !== null ) {
			$this->file = fopen( $fileName, 'a' );
			if ( !$this->file ) {
				throw new \RuntimeException( 'Unable to open undo log' );
			}
		}
		$this->dbw = $dbw;
	}

	/**
	 * @param string $table
	 * @param array $newValues
	 * @param array $oldValues
	 * @param string $fname
	 * @return bool
	 */
	public function update( $table, array $newValues, array $oldValues, $fname ) {
		$this->dbw->newUpdateQueryBuilder()
			->update( $table )
			->set( $newValues )
			->where( $oldValues )
			->caller( $fname )->execute();

		$updated = (bool)$this->dbw->affectedRows();
		if ( $this->file && $updated ) {
			$table = $this->dbw->tableName( $table );
			fwrite(
				$this->file,
				"UPDATE $table" .
					' SET ' . $this->dbw->makeList( $oldValues, IDatabase::LIST_SET ) .
					' WHERE ' . $this->dbw->makeList( $newValues, IDatabase::LIST_AND ) . ";\n"
			);
		}
		return $updated;
	}
}
