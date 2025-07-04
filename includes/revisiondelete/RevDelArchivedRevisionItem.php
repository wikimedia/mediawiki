<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
