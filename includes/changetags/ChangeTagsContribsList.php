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
 * Stores a list of taggable contributions.
 * @since 1.26
 */
class ChangeTagsContribsList extends ChangeTagsRevisionList {
	public function getType() {
		return 'contribs';
	}

	/**
	 * @param DatabaseBase $db
	 * @return mixed
	 */
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		$queryInfo = array(
			'tables' => array( 'revision', 'user' ),
			'fields' => array_merge( Revision::selectFields(), Revision::selectUserFields() ),
			'conds' => array(
				'rev_id' => $ids,
			),
			'options' => array( 'ORDER BY' => 'rev_id DESC' ),
			'join_conds' => array(
				'page' => Revision::pageJoinCond(),
				'user' => Revision::userJoinCond(),
			),
		);
		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options']
		);
		return $db->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			__METHOD__,
			$queryInfo['options'],
			$queryInfo['join_conds']
		);
	}

	public function newItem( $row ) {
		return new ChangeTagsContribsItem( $this, $row );
	}
}
