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
 * @ingroup Change tagging
 */

/**
 * Generic list for change tagging.
 */
abstract class ChangeTagsList extends RevisionListBase {
	function __construct( IContextSource $context, Title $title, array $ids ) {
		parent::__construct( $context, $title );
		$this->ids = $ids;
	}

	/**
	 * Creates a ChangeTags*List of the requested type.
	 *
	 * @param string $typeName 'revision' or 'logentry'
	 * @param IContextSource $context
	 * @param Title $title
	 * @param array $ids
	 * @return ChangeTagsList An instance of the requested subclass
	 * @throws Exception If you give an unknown $typeName
	 */
	public static function factory( $typeName, IContextSource $context,
		Title $title, array $ids ) {

		switch ( $typeName ) {
			case 'revision':
				$className = 'ChangeTagsRevisionList';
				break;
			case 'logentry':
				$className = 'ChangeTagsLogList';
				break;
			default:
				throw new Exception( "Class $typeName requested, but does not exist" );
		}

		return new $className( $context, $title, $ids );
	}

	/**
	 * Reload the list data from the master DB.
	 */
	function reloadFromMaster() {
		$dbw = wfGetDB( DB_MASTER );
		$this->res = $this->doQuery( $dbw );
	}

	/**
	 * Add/remove change tags from all the items in the list.
	 *
	 * @param array $tagsToAdd
	 * @param array $tagsToRemove
	 * @param array $params
	 * @param string $reason
	 * @param User $user
	 * @return Status
	 */
	abstract function updateChangeTagsOnAll( $tagsToAdd, $tagsToRemove, $params,
		$reason, $user );
}
