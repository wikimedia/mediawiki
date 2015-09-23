<?php
/**
 * Updater for link tracking tables after a page edit.
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
 */

/**
 * Update object handling the cleanup of links tables after a page was deleted.
 **/
class LinksDeletionUpdate extends SqlDataUpdate {
	/** @var WikiPage The WikiPage that was deleted */
	protected $mPage;

	/**
	 * Constructor
	 *
	 * @param WikiPage $page Page we are updating
	 * @throws MWException
	 */
	function __construct( WikiPage $page ) {
		parent::__construct( false ); // no implicit transaction

		$this->mPage = $page;

		if ( !$page->exists() ) {
			throw new MWException( "Page ID not known, perhaps the page doesn't exist?" );
		}
	}

	/**
	 * Do some database updates after deletion
	 */
	public function doUpdate() {
		$title = $this->mPage->getTitle();
		$id = $this->mPage->getId();

		# Delete restrictions for it
		$this->mDb->delete( 'page_restrictions', array( 'pr_page' => $id ), __METHOD__ );

		# Fix category table counts
		$cats = array();
		$res = $this->mDb->select( 'categorylinks', 'cl_to', array( 'cl_from' => $id ), __METHOD__ );

		foreach ( $res as $row ) {
			$cats[] = $row->cl_to;
		}

		$this->mPage->updateCategoryCounts( array(), $cats );

		# If using cascading deletes, we can skip some explicit deletes
		if ( !$this->mDb->cascadingDeletes() ) {
			# Delete outgoing links
			$this->mDb->delete( 'pagelinks', array( 'pl_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'imagelinks', array( 'il_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'categorylinks', array( 'cl_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'templatelinks', array( 'tl_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'externallinks', array( 'el_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'langlinks', array( 'll_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'iwlinks', array( 'iwl_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'redirect', array( 'rd_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'page_props', array( 'pp_page' => $id ), __METHOD__ );
		}

		# If using cleanup triggers, we can skip some manual deletes
		if ( !$this->mDb->cleanupTriggers() ) {
			# Find recentchanges entries to clean up...
			$rcIdsForTitle = $this->mDb->selectFieldValues( 'recentchanges',
				'rc_id',
				array(
					'rc_type != ' . RC_LOG,
					'rc_namespace' => $title->getNamespace(),
					'rc_title' => $title->getDBkey()
				),
				__METHOD__
			);
			$rcIdsForPage = $this->mDb->selectFieldValues( 'recentchanges',
				'rc_id',
				array( 'rc_type != ' . RC_LOG, 'rc_cur_id' => $id ),
				__METHOD__
			);

			# T98706: delete PK to avoid lock contention with RC delete log insertions
			$rcIds = array_merge( $rcIdsForTitle, $rcIdsForPage );
			if ( $rcIds ) {
				$this->mDb->delete( 'recentchanges', array( 'rc_id' => $rcIds ), __METHOD__ );
			}
		}
	}
}