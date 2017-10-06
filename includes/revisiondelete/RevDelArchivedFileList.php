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

use Wikimedia\Rdbms\IDatabase;

/**
 * List for filearchive table items
 */
class RevDelArchivedFileList extends RevDelFileList {
	public function getType() {
		return 'filearchive';
	}

	public static function getRelationType() {
		return 'fa_id';
	}

	/**
	 * @param IDatabase $db
	 * @return mixed
	 */
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );

		$fileQuery = ArchivedFile::getQueryInfo();
		return $db->select(
			$fileQuery['tables'],
			$fileQuery['fields'],
			[
				'fa_name' => $this->title->getDBkey(),
				'fa_id' => $ids
			],
			__METHOD__,
			[ 'ORDER BY' => 'fa_id DESC' ],
			$fileQuery['joins']
		);
	}

	public function newItem( $row ) {
		return new RevDelArchivedFileItem( $this, $row );
	}
}
