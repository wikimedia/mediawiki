<?php
/**
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
 * @ingroup Change tagging
 */

/**
 * Efficiently retrieves properties of change tags using page_props
 * @since 1.27
 */
class ChangeTagsArray {

	/**
	 * @var array Array mapping tags to article Ids
	 */
	protected $tagIds = null;

	/**
	 * @var array Array mapping article Ids to arrays
	 */
	protected $tagProps = null;

	/**
	 * @var array
	 */
	private $baseArray;

	/**
	 * @param array $list Array of tags to create the ChangeTagsArray from
	 * @since 1.27
	 */
	public function __construct( $list ) {
		$this->baseArray = $list;
	}

	/**
	 * Maps each $tag to the article id of its associated Tag-$tag page,
	 * when it exists
	 *
	 * @return array Array of tags mapped to article ids
	 * @since 1.27
	 */
	public function getIds() {
		// Save in class if not already done
		if ( $this->tagIds === null ) {
			foreach ( $this->baseArray as $tag ) {
				$tagTitle = Title::makeTitle( NS_MEDIAWIKI, 'Tag-' . $tag );
				$id = $tagTitle->getArticleID();
				if ( $id ) {
					$this->tagIds[$tag] = $id;
				}
			}
		}
		return $this->tagIds;
	}

	/**
	 * Maps each article id of an existing Tag-$tag page to an array
	 * mapping tag-related page_prop properties to their values
	 *
	 * @return array Array of article ids mapped to arrays
	 * @since 1.27
	 */
	public function getProps() {
		// Save in class if not already done
		if ( $this->tagProps === null ) {
			$this->tagProps = array();
			if ( $this->getIds() ) {
				$dbr = wfGetDB( DB_SLAVE );
				$fields = array( 'pp_page', 'pp_propname', 'pp_value' );
				$validIds = array_values( $this->getIds() );
				$res = $dbr->select( 'page_props', $fields, array(
					'pp_propname' => ChangeTags::validProps(),
					'pp_page' => $validIds,
				) );
				foreach ( $res as $row ) {
					$this->tagProps[$row->pp_page][$row->pp_propname] = $row->pp_value;
				}
			}
		}
		return $this->tagProps;
	}

	/**
	 * Returns whether at least one of the tags is marked as problem
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function containsProblem() {
		foreach ( $this->getProps() as $id => &$props ) {
			if ( isset( $props['changetagproblem'] ) ) {
				return true;
			}
		}
		return false;
	}
}
