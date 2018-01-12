<?php
/**
 * Populate the content and slot tables.
 *
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Usage:
 *  populateContentTables.php --batch-size 200
 */
class PopulateContentTables extends Maintenance {

	// todo get these from the slot_roles table
	const SLOT_NAME_MAIN = 1;

	const SLOT_INHERITED = 1;
	const SLOT_NOT_INHERITED = 0;

	/**
	 * @var IDatabase
	 */
	private $dbw;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Populate content and slot tables' );
		$this->setBatchSize( 100 );
	}

	public function execute()
	{
		$batchSize = $this->getBatchSize();
		$lastRevId = 0;

		$this->dbw = $this->getDB( DB_MASTER );

		do {
			$rows = $this->fetchRevisionIds( $lastRevId, $batchSize );
			$contentRows = [];

			foreach ( $rows as $revisionRow ) {
				$lastRevId = (int)$revisionRow->rev_id;

				$contentRow = $this->buildContentRowData( $lastRevId );

				$this->dbw->begin( __METHOD__ );

				$contentId = $this->insertContentRow( $contentRow );
				$this->insertSlotRow( $lastRevId, $contentId );

				$this->dbw->commit( __METHOD__ );
			}

			$this->writeln( 'Processed up to revision id ' . $lastRevId );

			// TODO wait for replication
		} while ( $rows->numRows() >= $batchSize );

		$this->writeln( 'Done' );
	}

	/**
	 * @param int $revisionId
	 * @return array
	 */
	private function buildContentRowData( $revisionId ) {
		$revision = Revision::newFromId( $revisionId );

		$contentModel = $revision->getContentModel();
		$contentModelId = $this->getContentModelId( $contentModel );

		$contentData = [
			'content_size' => $revision->getSize(),
			'content_sha1' => $revision->getSha1(),
			'content_model' => $contentModelId,
			'content_address' => 'tt:' . $revision->getTextId()
		];

		return $contentData;
	}

	/**
	 * @param string $contentModelName
	 * @return int
	 */
	private function getContentModelId( $contentModelName ) {
		$res = $this->dbw->selectRow(
			'content_models',
			[ 'model_id' ],
			[ 'model_name' => $contentModelName ],
			__METHOD__
		);

		if ($res) {
			return (int)$res->model_id;
		}

		return $this->addContentModelTableRecord( $contentModelName );
	}

	/**
	 * @param string $contentModelName
	 * @return int
	 */
	private function addContentModelTableRecord( $contentModelName ) {
		$res = $this->dbw->insert(
			'content_models',
			[
				'model_name' => $contentModelName
			],
			__METHOD__
		);

		if (!$res) {
			// error
		}

		return $this->dbw->insertId();
	}

	/**
	 * @param int $revId
	 * @param int $contentId
	 * @return mixed
	 */
	private function insertSlotRow( $revId, $contentId ) {
		$res = $this->dbw->insert(
			'slots',
			$this->getSlotData( $revId, $contentId ),
			__METHOD__
		);

		return $res;
	}

	/**
	 * @param int $revId
	 * @param int $contentId
	 * @return array
	 */
	private function getSlotData( $revId, $contentId ) {
		$slotData = [
			'slot_revision_id' => $revId,
			'slot_role_id' => self::SLOT_NAME_MAIN,
			'slot_content_id' => $contentId,
			'slot_inherited' => self::SLOT_NOT_INHERITED
		];

		return $slotData;
	}

	/**
	 * @param array $rowValues
	 * @return mixed
	 */
	private function insertContentRow( array $rowValues ) {
		$res = $this->dbw->insert(
			'content',
			$rowValues,
			__METHOD__
		);

		return $this->dbw->insertId();
	}

	private function fetchRevisionIds( $lastId, $batchSize )
	{
		// $table, $vars, $conds, $fname, $options, $join_conds
		$rows = $this->dbw->select(
			[ 'revision', 'slots' ],
			[ 'rev_id' ],
			[
				"rev_id > $lastId",
				'slot_revision_id IS NULL'
			],
			__METHOD__,
			[
				'LIMIT' => $batchSize,
				'ORDER BY' => 'rev_id ASC'
			],
			[
				'slots' => [ 'LEFT JOIN', 'rev_id=slot_revision_id' ]
			]
		);

		return $rows;
	}

	/**
	 * @param string $msg
	 */
	private function writeln( $msg ) {
		$this->output( "$msg\n" );
	}
}

$maintClass = 'PopulateContentTables';
require_once RUN_MAINTENANCE_IF_MAIN;