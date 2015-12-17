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
 * Stores a list of taggable revisions.
 * @since 1.25
 */
class ChangeTagsRevisionList extends ChangeTagsList {
	public function getType() {
		return 'revision';
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
				'rev_page' => $this->title->getArticleID(),
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
			$queryInfo['options'],
			''
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
		return new ChangeTagsRevisionItem( $this, $row );
	}

	/**
	 * Add/remove change tags from all the revisions in the list.
	 *
	 * @param array $tagsToAdd
	 * @param array $tagsToRemove
	 * @param array $params
	 * @param string $reason
	 * @param User $user
	 * @return Status
	 */
	public function updateChangeTagsOnAll( $tagsToAdd, $tagsToRemove, $params,
		$reason, $user ) {

		// @codingStandardsIgnoreStart Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed
		for ( $this->reset(); $this->current(); $this->next() ) {
			// @codingStandardsIgnoreEnd
			$item = $this->current();
			$status = ChangeTags::updateTagsWithChecks( $tagsToAdd, $tagsToRemove,
				null, $item->getId(), null, $params, $reason, $user );
			// Should only fail on second and subsequent times if the user trips
			// the rate limiter
			if ( !$status->isOK() ) {
				break;
			}
		}

		return $status;
	}
}
