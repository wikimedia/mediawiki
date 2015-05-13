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
 * List for logging table items
 */
class RevDelContribsList extends RevDelList {
	/**
	* @var array
	*/
	protected $latestIds;

	function __construct( IContextSource $context, Title $title, array $ids ) {
		parent::__construct( $context, $title, $ids );

		// Make array mapping ids to the latest rev id of the title the id belongs to
		$latest = array();
		foreach ( $ids as $id ) {
			$rev = Revision::newFromId( $id );
			$latest[$id]= $rev->getTitle()->getLatestRevID();
		}

		// Map each latest rev id to the set of ids belonging to the same title
		$map = array();
		foreach ( array_unique( $latest ) as $latestRevID ) {
			$map[$latestRevID] = array_keys( $latest, $latestRevID );
		}

		$this->latestIds = $map;
	}

	public function getLatestIds() {
		return $this->latestIds;
	}

	public function getType() {
		return 'revision';
	}

	public static function getRelationType() {
		return 'rev_id';
	}

	public static function getRestriction() {
		return 'deleterevision';
	}

	public static function getRevdelConstant() {
		return Revision::DELETED_TEXT;
	}

	public static function suggestTarget( $target, array $ids ) {
		$rev = Revision::newFromId( $ids[0] );
		return SpecialPage::getTitleFor( 'Contributions', $rev->getUserText( Revision::RAW ) );
	}

	/**
	 * @param IDatabase $db
	 * @return mixed
	 */
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		$live = $db->select(
			array( 'revision', 'page', 'user' ),
			array_merge( Revision::selectFields(), Revision::selectUserFields() ),
			array(
				'rev_id' => $ids,
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_id DESC' ),
			array(
				'page' => Revision::pageJoinCond(),
				'user' => Revision::userJoinCond() )
		);

		// Revisions in the archive table are ignored.
		return $live;
	}

	public function newItem( $row ) {
		if ( isset( $row->rev_id ) ) {
			return new RevDelContribsItem( $this, $row );
		} elseif ( isset( $row->ar_rev_id ) ) {
			return;
		} else {
			// This shouldn't happen. :)
			throw new MWException( 'Invalid row type in RevDelRevisionList' );
		}
	}

	public function getSuppressBit() {
		return Revision::DELETED_RESTRICTED;
	}

	public function doPreCommitUpdates() {
		foreach ( array_keys( $this->latestIds ) as $id ) {
			$rev = Revision::newFromId( $id );
			$rev->getTitle()->invalidateCache();
		}
		return Status::newGood();
	}

	public function doPostCommitUpdates() {
		foreach ( array_keys( $this->latestIds ) as $id ) {
			$rev = Revision::newFromId( $id );
			$rev->getTitle()->purgeSquid();
			// Extensions that require referencing previous revisions may need this
			Hooks::run( 'ArticleRevisionVisibilitySet', array( $rev->getTitle(), $id ) );
		}
		return Status::newGood();
	}

	protected function updateLog( $params ) {
		// Add one log entry by target page
		foreach ( $this->latestIds as $latestRevID => $ids ) {
			$entryParams = $params;
			// We keep only rev ids of the target page that were actually updated.
			$entryParams['ids'] = array_intersect( $params['ids'], $ids );
			$entryParams['count'] = count( $entryParams['ids'] );
			if ( !$entryParams['count'] ) {
				// Ignore if this target page had no revision updated
				continue;
			}
			$entryParams['title'] = Revision::newFromId( $latestRevID )->getTitle();
			$this->addLogEntry( $entryParams );
		}
	}
}
