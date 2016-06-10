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

/**
 * List for archive table items, i.e. revisions deleted via action=delete
 */
class RevDelArchiveList extends RevDelRevisionList {
	public function getType() {
		return 'archive';
	}

	public static function getRelationType() {
		return 'ar_timestamp';
	}

	/**
	 * @param IDatabase $db
	 * @return mixed
	 */
	public function doQuery( $db ) {
		$timestamps = [];
		foreach ( $this->ids as $id ) {
			$timestamps[] = $db->timestamp( $id );
		}

		$tables = [ 'archive' ];
		$fields = Revision::selectArchiveFields();
		$conds = [
			'ar_namespace' => $this->title->getNamespace(),
			'ar_title' => $this->title->getDBkey(),
			'ar_timestamp' => $timestamps,
		];
		$join_conds = [];
		$options = [ 'ORDER BY' => 'ar_timestamp DESC' ];

		ChangeTags::modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$options,
			''
		);

		return $db->select( $tables,
			$fields,
			$conds,
			__METHOD__,
			$options,
			$join_conds
		);
	}

	public function newItem( $row ) {
		return new RevDelArchiveItem( $this, $row );
	}

	public function doPreCommitUpdates() {
		return Status::newGood();
	}

	public function doPostCommitUpdates( array $visibilityChangeMap ) {
		return Status::newGood();
	}
}
