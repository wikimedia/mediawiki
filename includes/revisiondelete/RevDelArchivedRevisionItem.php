<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup RevisionDelete
 */

use MediaWiki\RevisionList\RevisionListBase;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Item class for a archive table row by ar_rev_id -- actually
 * used via RevDelRevisionList.
 */
class RevDelArchivedRevisionItem extends RevDelArchiveItem {

	protected IConnectionProvider $dbProvider;

	/**
	 * @param RevisionListBase $list
	 * @param stdClass $row
	 * @param IConnectionProvider $dbProvider
	 */
	public function __construct( RevisionListBase $list, stdClass $row, IConnectionProvider $dbProvider ) {
		$this->dbProvider = $dbProvider;
		parent::__construct( $list, $row );
	}

	/** @inheritDoc */
	public function getIdField(): string {
		return 'ar_rev_id';
	}

	/** @inheritDoc */
	public function getId(): int {
		return $this->getRevisionRecord()->getId();
	}

	/**
	 * @param int $bits
	 * @return bool
	 */
	public function setBits( $bits ): bool {
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->newUpdateQueryBuilder()
			->update( 'archive' )
			->set( [ 'ar_deleted' => $bits ] )
			->where( [
				'ar_rev_id' => $this->row->ar_rev_id,
				'ar_deleted' => $this->getBits(),
			] )
			->caller( __METHOD__ )->execute();

		return (bool)$dbw->affectedRows();
	}
}
